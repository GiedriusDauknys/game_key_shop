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
        $admin = Role::firstorcreate(['name' => 'admin']);
        $member = Role::create(['name' => 'member']);


        // Priskiriame role adminui
        $user = \App\Models\User::find(1); // Priskiriame pirmam vartotojui
        if ($user) {
            $user->assignRole('admin');
        }
    }
}
