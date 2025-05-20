<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Sukuriame roles
        $admin = Role::create(['name' => 'admin']);
        $member = Role::create(['name' => 'member']);

        // Sukuriame permissions
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage games']);

        // Priskiriame permissions adminui
        $admin->givePermissionTo(['manage users', 'manage games']);

        // Priskiriame role adminui
        $user = \App\Models\User::find(1); // Priskiriame pirmam vartotojui
        if ($user) {
            $user->assignRole('admin');
        }
    }
}
