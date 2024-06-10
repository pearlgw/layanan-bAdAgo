<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Barang;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

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

        for ($i = 1; $i <= 11; $i++) {
            Barang::create([
                'id' => Uuid::uuid4()->toString(),
                'kode_barang' => "BRGTKB000{$i}",
                'nama_barang' => "Barang TOKO B{$i}",
                'stok' => mt_rand(10, 20),
                'harga_jual' => mt_rand(2000, 2000000),
            ]);
        }
    }
}
