<?php
namespace App\Imports;

use App\Models\Payments;
use App\Models\Condominiums;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PaymentsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, WithMapping, SkipsEmptyRows
{
    use Importable, SkipsFailures;

    private $rows = 0;
    private $totalRows = 0;

    /**
     * Evita que las filas vacías entren al proceso de validación o conteo
     */
    public function isEmpty(array $row): bool
    {
        // Si el número de condominio y el monto están vacíos, ignoramos la fila
        return empty($row['condominio']) && empty($row['monto']);
    }

    /**
     * Mapeo: Limpiamos los datos antes de validar
     */
    public function map($row): array
    {
        try {
            $fechaRaw = $row['fecha_y_hora'];
            $fechaFormateada = is_numeric($fechaRaw) 
                ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fechaRaw)->format('Y-m-d H:i:s')
                : Carbon::parse($fechaRaw)->format('Y-m-d H:i:s');
            $this->totalRows++;
        } catch (\Exception $e) {
            $fechaFormateada = $row['fecha_y_hora'];
        }

        return [
            'condominio'   => $row['condominio'],
            'monto'        => $row['monto'],
            'moneda'       => $row['moneda'] ?? 'MXN',
            'descuento'    => $row['descuento'] ?? 0,
            'fecha_y_hora' => $fechaFormateada,
        ];
    }

    public function rules(): array
    {
        return [
            'condominio'   => 'required|exists:condominiums,number',
            'monto'        => 'required|numeric',
            'fecha_y_hora' => [
                'required',
                Rule::unique('payments', 'created_at'),
            ],
        ];
    }

    public function model(array $row)
    {
        
        $condominium = Condominiums::where('number', $row['condominio'])->first();
        $this->rows++;
        return new Payments([
            'id_condominium'       => $condominium->id,
            'amount'               => $row['monto'],
            'currency'             => $row['moneda'],
            'discount_condominium' => $row['descuento'],
            'created_at'           => $row['fecha_y_hora'],
        ]);
    }

    public function getRowCount(): int 
    { 
        return $this->rows; 
    }

    public function getTotalRows(): int 
    { 
        return $this->totalRows; 
    }
}