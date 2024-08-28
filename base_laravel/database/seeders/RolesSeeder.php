<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesSeeder extends Seeder
{
    public function run()
    {
        Role::create(['name' => 'sysadmin']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'user']);

        $user = User::find(1);
        if ($user) {
            $user->assignRole('sysadmin');
        }
    }
}