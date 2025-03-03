<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Role::create([
            'role_name' => 'user',
        ]);

        User::create([
            'name' => 'gayuh',
            'email' => 'gayuh@gmail.com',
            'password' => Hash::make('password'),
            'no_telp' => '089738383939',
            'provinsi' => 'Jawa Tengah',
            'kota' => 'Semarang'
        ]);
        User::create([
            'name' => 'hasan',
            'email' => 'hasan@gmail.com',
            'password' => Hash::make('password'),
            'no_telp' => '089738383939',
            'provinsi' => 'Jawa Barat',
            'kota' => 'Bandung'
        ]);

        $this->call(LocationsSeeder::class);
    }
}