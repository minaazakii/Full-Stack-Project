<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{

    public function run(): void
    {
       $admin = User::firstOrCreate(
            [
                'email' => 'mina@admin.com'
            ],
            [
                'name' => 'Admin Mina',
                'password' => Hash::make('123456')
            ]
        );
        $admin->syncPermissions(Permission::all());
        $admin->syncRoles(Role::first());
        User::factory(100)->create();
    }
}
