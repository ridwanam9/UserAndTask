<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'id' => Str::uuid(),
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => true
        ]);

        User::create([
            'id' => Str::uuid(),
            'name' => 'Manager',
            'email' => 'manager@email.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'status' => true
        ]);

        User::create([
            'id' => Str::uuid(),
            'name' => 'Manager1',
            'email' => 'manager1@email.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'status' => true
        ]);

        User::create([
            'id' => Str::uuid(),
            'name' => 'Staff1',
            'email' => 'staff1@email.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'status' => true
        ]);

        User::create([
            'id' => Str::uuid(),
            'name' => 'Staff2',
            'email' => 'staff2@email.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'status' => true
        ]);

        User::create([
            'id' => Str::uuid(),
            'name' => 'Staff3',
            'email' => 'staff3@email.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'status' => true
        ]);
    }
}
