<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        User::firstOrCreate(
            [
                'email' => 'mina@admin.com'
            ],
            [
                'name' => 'Admin Mina',
                'password' => Hash::make('123456')
            ]
        );
        User::factory(100)->create();
    }
}
