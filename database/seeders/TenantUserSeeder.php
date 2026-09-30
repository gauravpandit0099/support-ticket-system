<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TenantUserSeeder extends Seeder
{
    public function run(): void
    {
        $acme = Tenant::create([
            'name' => 'Acme Technologies',
        ]);

        $globex = Tenant::create([
            'name' => 'Globex Corporation',
        ]);

        User::create([
            'tenant_id' => $acme->id,
            'name' => 'Acme Admin',
            'email' => 'admin@acme.test',
            'mobile' => '9000000001',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'tenant_id' => $acme->id,
            'name' => 'Acme Employee',
            'email' => 'employee@acme.test',
            'mobile' => '9000000002',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);

        User::create([
            'tenant_id' => $globex->id,
            'name' => 'Globex Admin',
            'email' => 'admin@globex.test',
            'mobile' => '9000000003',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'tenant_id' => $globex->id,
            'name' => 'Globex Employee',
            'email' => 'employee@globex.test',
            'mobile' => '9000000004',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);
    }
}