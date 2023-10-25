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
        User::create([
            'name'=>'Admin Mina',
            'email'=>'mina@admin.com',
            'password'=>Hash::make('123456')
        ]);
        User::factory(100)->create();
    }
}
