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

        // kode souvenir dan nama souvenir
        $kode_souvenir = [
            'MUG',
            'TSH',
            'CAP',
            'TUM',
            'BAG',
            'BOT',
            'JAR',
            'PEN',
            'USB',
            'KEY',
            'LAP',
            'TOW',
            'UMB',
            'PIL',
            'STA',
            'CAL',
            'WAL',
            'BAN',
            'TIE',
            'CLO',
        ];

        $nama_souvenir = [
            'Mug',
            'T-Shirt',
            'Cap',
            'Tumbler',
            'Bag',
            'Bottle',
            'Jar',
            'Pen',
            'USB',
            'Keychain',
            'Laptop Sleeve',
            'Towel',
            'Umbrella',
            'Pillow',
            'Sticker',
            'Calendar',
            'Wallet',
            'Bandana',
            'Tie',
            'Cloth',
        ];

        $nama_toko = "Souvenir Shop A";
        $kota = 'Jakarta Pusat';
        $provinsi = 'DKI Jakarta';
        
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