<?php

namespace App\Imports;

use App\Models\Conductor;
use App\Models\EventoConductor;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvitadosImport implements ToModel, WithHeadingRow
{
    protected $eventoId;

    // Contadores para logging
    protected static $contador = 0;
    protected static $procesados = 0;
    protected static $errores = 0;

    public function __construct($eventoId)
    {
        $this->eventoId = $eventoId;
    }

    public function model(array $row)
    {
        self::$contador++;

        try {
            // Normalizar claves
            $row = array_combine(
                array_map('trim', array_keys($row)),
                array_values($row)
            );

            Log::info('Valor recibido para asiste:', ['asiste' => $row['asiste'] ?? null]);

            // Validación mínima: debe tener email
            if (empty($row['email'])) {
                Log::warning("Fila #" . self::$contador . " ignorada: sin email", $row);
                return null;
            }

            $conductor = Conductor::updateOrCreate(
                ['email' => $this->valorOrNull($row['email'] ?? null)],
                [
                    'empresa' => $this->valorOrNull($row['empresa'] ?? null),
                    'cif' => $this->valorOrNull($row['cif'] ?? null),
                    'nombre' => $this->valorOrNull($row['nombre'] ?? null),
                    'apellido' => $this->valorOrNull($row['apellido'] ?? null),
                    'telefono' => $this->valorOrNull($row['telefono'] ?? null),
                    'dni' => $this->valorOrNull($row['dni'] ?? null),
                    'kam' => $this->valorOrNull($row['kam'] ?? null),
                    'asiste' => isset($row['asiste']) && in_array(strtolower(trim($row['asiste'])), ['si', 'sí', 's', '1'], true) ? 1 : 0,
                    'vehiculo_prop' => $this->valorOrNull($row['vehiculo_prop'] ?? null),
                    'vehiculo_emp' => $this->valorOrNull($row['vehiculo_emp'] ?? null),
                    'intolerancia' => $this->valorOrNull($row['intolerancia'] ?? null),
                    'preferencia' => $this->valorOrNull($row['preferencia'] ?? null),
                    'carnet' => $this->valorOrNull($row['carnet'] ?? null),
                    'etiqueta' => $this->valorOrNull($row['etiqueta'] ?? null),
                    'proteccion_datos' => $this->valorOrNull($row['proteccion_datos'] ?? null),
                    'carnet_caducidad' => $this->valorOrNull($row['carnet_caducidad'] ?? null),
                ]
            );

            EventoConductor::firstOrCreate([
                'conductor_id' => $conductor->id,
                'evento_id' => $this->eventoId,
            ]);

            self::$procesados++;

            return $conductor;
        } catch (\Throwable $e) {
            self::$errores++;
            Log::error("Error en fila #" . self::$contador . ": " . $e->getMessage(), $row);
            return null;
        }
    }

    private function valorOrNull($value)
    {
        $val = strtolower(trim((string) $value));
        $invalidos = ['', '-', '--', '---', 'n/a', 'sin info', 'ninguna', 'null', 'na', 'n/a.', 'n.d.', 's/n'];

        // Si es un número y está en el rango típico de fechas de Excel
        if (is_numeric($value) && $value > 20000 && $value < 60000) {
            // Convierte el número de Excel a fecha Y-m-d
            $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            return $fecha->format('Y-m-d');
        }

        return in_array($val, $invalidos, true) ? null : trim($value);
    }
}
