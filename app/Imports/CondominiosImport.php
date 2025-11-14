<?php

namespace App\Imports;

use App\Models\Condominiums;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;

class CondominiosImport implements OnEachRow
{
    public function onRow(Row $row)
    {
        $r = $row->toArray();

        if(Condominiums::where('tower', $r[0])->where('number', $r[1])->exists()){
            return;
        }

        // Condominium::create([
        //     ''
        // ]);
    }
}
