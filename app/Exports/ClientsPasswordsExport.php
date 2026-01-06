<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class ClientsPasswordsExport implements FromCollection
{

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->data);
    }

    public function headings():array{
        return ['Numero de contrato', 'Nombre', 'Contraseña temporal'];
    }
}
