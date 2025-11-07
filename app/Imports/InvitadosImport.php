<?php

namespace App\Imports;

use App\Models\Conductor;
use App\Models\Evento;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvitadosImport implements ToModel, WithHeadingRow
{
    protected int $eventoId;

    // Contadores (opcional)
    protected static int $contador = 0;
    protected static int $procesados = 0;
    protected static int $errores = 0;

    public function __construct(int $eventoId)
    {
        $this->eventoId = $eventoId;
    }

    public function model(array $row)
    {
        self::$contador++;

        try {
            // Normaliza keys (trim headers)
            $row = array_combine(
                array_map('trim', array_keys($row)),
                array_values($row)
            );

            // 1) Validación mínima
            if (empty($row['email'])) {
                Log::warning("Fila #".self::$contador." ignorada: sin email", $row);
                return null;
            }

            // 2) Upsert del Conductor (modelo base)
            $conductor = Conductor::updateOrCreate(
                ['email' => $this->clean($row['email'] ?? null)],
                [
                    'empresa'          => $this->clean($row['empresa'] ?? null),
                    'cif'              => $this->clean($row['cif'] ?? null),
                    'nombre'           => $this->clean($row['nombre'] ?? null),
                    'apellido'         => $this->clean($row['apellido'] ?? null),
                    'telefono'         => $this->clean($row['telefono'] ?? null),
                    'dni'              => $this->clean($row['dni'] ?? null),
                    'carnet_caducidad' => $this->excelDate($row['carnet_caducidad'] ?? null),
                ]
            );

            // 3) Snapshot al PIVOT del evento actual
            $pivotData = [
                'cif'               => $this->clean($row['cif'] ?? null),
                'nombre'            => $this->clean($row['nombre'] ?? null),
                'apellido'          => $this->clean($row['apellido'] ?? null),
                'email'             => $this->clean($row['email'] ?? null),
                'telefono'          => $this->clean($row['telefono'] ?? null),
                'empresa'           => $this->clean($row['empresa'] ?? null),
                'vehiculo_prop'     => $this->yn($row['vehiculo_prop'] ?? null), // 'si'/'no'/null
                'vehiculo_emp'      => $this->yn($row['vehiculo_emp'] ?? null),
                'etiqueta'          => $this->clean($row['etiqueta'] ?? null),
                'intolerancia'      => $this->clean($row['intolerancia'] ?? null),
                'preferencia'       => $this->clean($row['preferencia'] ?? null),
                'kam'               => $this->clean($row['kam'] ?? null),
                'asiste'            => $this->toBool01($row['asiste'] ?? null),
                'dni'               => $this->clean($row['dni'] ?? null),
                'proteccion_datos'  => $this->toBool01($row['proteccion_datos'] ?? null),
                'carnet_caducidad'  => $this->excelDate($row['carnet_caducidad'] ?? null),
                // 'observaciones'   => $this->clean($row['observaciones'] ?? null),
                // 'carnet'          => path si lo importaras como archivo (normalmente no viene en Excel)
            ];

            // 4) Adjuntar/sincronizar pivot
            $evento = Evento::findOrFail($this->eventoId);
            $evento->invitados()->syncWithoutDetaching([
                $conductor->id => $pivotData
            ]);

            self::$procesados++;
            return $conductor;

        } catch (\Throwable $e) {
            self::$errores++;
            Log::error("Error en fila #".self::$contador.": ".$e->getMessage(), $row);
            return null;
        }
    }

    /** Limpia valores tipo '-', 'n/a', '', etc. a null */
    private function clean($value): ?string
    {
        if ($value === null) return null;
        $val = trim((string)$value);
        if ($val === '') return null;

        $lower = mb_strtolower($val);
        $invalid = ['-', '--', '---', 'n/a', 'na', 'n.d.', 'null', 'ninguna', 'sin info', 's/n'];
        return in_array($lower, $invalid, true) ? null : $val;
    }

    /** Convierte “sí/si/s/1/true” => 1, “no/0/false/-/vacío” => 0, otro => 0 */
    private function toBool01($value): int
    {
        if ($value === null) return 0;
        $v = mb_strtolower(trim((string)$value));
        return in_array($v, ['si','sí','s','1','true','t','y','yes'], true) ? 1 : 0;
    }

    /** Normaliza 'si'/'no' o null (para campos tipo vehiculo_prop/emp) */
    private function yn($value): ?string
    {
        if ($value === null) return null;
        $v = mb_strtolower(trim((string)$value));
        if (in_array($v, ['si','sí','s','1','true','y','yes'], true)) return 'si';
        if (in_array($v, ['no','0','false','n'], true)) return 'no';
        return null;
    }

    /** Excel serial date o string fecha -> 'Y-m-d' | null */
    private function excelDate($value): ?string
    {
        if ($value === null || $value === '') return null;

        // numérico tipo Excel
        if (is_numeric($value)) {
            try {
                $dt = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                return $dt->format('Y-m-d');
            } catch (\Throwable $e) {
                return null;
            }
        }

        // string fecha
        try {
            return \Carbon\Carbon::parse((string)$value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
}
