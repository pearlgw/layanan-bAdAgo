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

        // nama dan kode souvenir kerajinan tangan dan dekorasi rumah
        $nama_souvenir = [
            'Kerajinan Tangan Kain Flanel',
            'Kerajinan Tangan Kain Perca',
            'Kerajinan Tangan Kain Batik',
            'Kerajinan Tangan Kain Tenun',
            'Kerajinan Tangan Kain Rajut',
            'Kerajinan Tangan Kain Sutera',
            'Kerajinan Tangan Kain Songket',
            'Kerajinan Tangan Kain Ulos',
            'Kerajinan Tangan Kain Endek',
            'Kerajinan Tangan Kain Ikat',
            'Kerajinan Tangan Kain Lurik',
            'Kerajinan Tangan Kain Sembalun',
            'Kerajinan Tangan Kain Sasirangan',
            'Kerajinan Tangan Kain Troso',
            'Kerajinan Tangan Kain Tenun Lombok',
            'Kerajinan Tangan Kain Tenun Sumba',
            'Kerajinan Tangan Kain Tenun Flores',
            'Kerajinan Tangan Kain Tenun Timor',
            'Kerajinan Tangan Kain Tenun Sulawesi',
            'Kerajinan Tangan Kain Tenun Papua',
        ];

        $kode_souvenir = [
            'KT-FL',
            'KT-PR',
            'KT-BT',
            'KT-TN',
            'KT-RJ',
            'KT-ST',
            'KT-SK',
            'KT-UL',
            'KT-ED',
            'KT-IK',
            'KT-LR',
            'KT-SB',
            'KT-SR',
            'KT-TR',
            'KT-TL',
            'KT-TS',
            'KT-TF',
            'KT-TT',
            'KT-TS',
            'KT-TP',
        ];

        $nama_toko = "Souvenir Shop B";
        $kota = 'Bandung';
        $provinsi = 'Jawa Barat';
        
        for ($i = 0; $i < 20; $i++) {
            Barang::create([
                'id' => Uuid::uuid4()->toString(),
                'kode_barang' => $kode_souvenir[$i],
                'nama_barang' => $nama_souvenir[$i],
                'stok' => rand(1, 100),
                'kota' => $kota,
                'provinsi' => $provinsi,
                'weight' => mt_rand(100, 10000),
                'nama_toko' => $nama_toko,
                'harga_jual' => rand(1000, 100000),
            ]);
        }
    }
}
