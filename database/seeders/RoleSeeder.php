<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'condominium.view',
            'condominium.create',
            'condominium.edit',
            'condominium.delete',

            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.delete',

            'documents.view',
            'documents.create',
            'documents.edit',

            'payment.view',
            'payment.create',
            'payment.edit',

            'orderPayment.view',
            'orderPayment.create',
            'orderPayment.edit',
            'orderPayment.delete',
        ];

        $admin = Role::firstOrCreate(['name'=>'admin']);
        $supervisor = Role::firstOrCreate(['name'=>'supervisor']);
        Role::firstOrCreate(['name'=>'client']);


        foreach($permissions as $value){
            Permission::create(['name' => $value]);
        }

        $admin->syncPermissions($permissions);
        $supervisor->syncPermissions([
            'condominium.view',
            'clients.view',
            'documents.view',
            'payment.view',
            'orderPayment.view',
        ]);

    }
}
