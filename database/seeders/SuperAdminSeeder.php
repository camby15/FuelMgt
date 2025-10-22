<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\SuperAdmin;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        SuperAdmin::create([
            'username' => 'Master',
            'password' => Hash::make('Master@1'),
        ]);
    }
}
