<?php

namespace App\Imports;

use App\Models\Coch;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class CochImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, WithBatchInserts, WithChunkReading
{
    use SkipsFailures;

    private $evento_id;
    private int $duplicados = 0;

    // ¡STATIC! Para que sobreviva entre chunks
    private static array $matriculasProcesadas = [];

    public function __construct($evento_id)
    {
        $this->evento_id = $evento_id;
    }

    public function model(array $row)
    {
        // Normalizar matrícula
        $matricula = strtoupper(preg_replace('/\s+/', '', trim($row['Matricula'])));

        // Evitar duplicados internos del archivo
        if (in_array($matricula, self::$matriculasProcesadas)) {
            $this->duplicados++;
            return null;
        }

        // Evitar duplicados en la base de datos
        $existe = Coch::whereRaw("REPLACE(UPPER(matricula), ' ', '') = ?", [$matricula])->exists();

        if ($existe) {
            $this->duplicados++;
            return null;
        }

        // Guardar en la colección temporal
        self::$matriculasProcesadas[] = $matricula;

        return new Coch([
            'evento_id' => $this->evento_id,
            'marca' => $row['Marca'],
            'modelo' => $row['Modelo'],
            'version' => $row['Version'],
            'matricula' => $matricula,
            'kam' => $row['KAM'],
            'asiste' => strtolower(trim($row['Llave'])) === 'si' ? 1 : 0
        ]);
    }

    public function rules(): array
    {
        return [
            'Marca' => 'required',
            'Modelo' => 'required',
            'Version' => 'required',
            'Matricula' => 'required',
            'KAM' => 'required',
            'Llave' => 'nullable|in:Si,No'
        ];
    }

    public function getDuplicados(): int
    {
        return $this->duplicados;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
