<?php

namespace Database\Seeders;
use Ramsey\Uuid\Uuid;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Barang;
use Illuminate\Database\Seeder;

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

        $kota = 'Jakarta Pusat';
        $provinsi = 'DKI Jakarta';

        for($i = 1; $i <= 11; $i++){
            Barang::create([
                'id' => Uuid::uuid4()->toString(),
                'kode_barang' => "BRG000{$i}",
                'nama_barang' => "Barang {$i}",
                'kota' => $kota,
                'provinsi' => $provinsi,
                'stok' => mt_rand(10, 20),
                'weight' => mt_rand(100, 10000),
                'harga_jual' => mt_rand(2000, 200000),
            ]);
        }
    }
}