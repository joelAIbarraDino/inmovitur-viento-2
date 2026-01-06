<?php

namespace App\Imports;

use App\Enums\LegalPersonality;
use App\Enums\MaritalPartnership;
use App\Models\Clients;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ClientsImport implements ToCollection, WithStartRow
{
    public $importedData = [];


    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach($rows as $row){
            
            $plainPassword = Str::random(10);

            $noContract = $row[0];
            $name = $row[1];
            $legalPersonality = $row[2];
            $maritalPartnership = $row[3];
            
            
            $user = User::create([
                'name'=>$name,
                'password'=>Hash::make($plainPassword)
            ]);
            
            $user->assignRole('client');
            
            $client = new Clients;
            $client->id_user             = $user->id;
            $client->no_contract         = $noContract;
            $client->legal_personality   = $legalPersonality ?? LegalPersonality::FISICA;
            $client->marital_partnership = $maritalPartnership ?? MaritalPartnership::NA;
            $client->save();
            
            $this->importedData[] = [
                'no_contract' => $client->no_contract,
                'name'        => $user->name,
                'password'    => $plainPassword,
            ];
        }
    }
}
