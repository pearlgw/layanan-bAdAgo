<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Keranjang;
use App\Models\Province;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kavist\RajaOngkir\Facades\RajaOngkir;

class BarangController extends Controller
{
    public function index()
    {
        $client1 = new Client();
        $url1 = "http://127.0.0.1:8001/api/barang";
        $response1 = $client1->request('GET', $url1);
        $content1 = $response1->getBody()->getContents();
        $contentArray1 = json_decode($content1, true);
        $data1 = $contentArray1['data'];

        $client2 = new Client();
        $url2 = "http://127.0.0.1:8002/api/barang";
        $response2 = $client2->request('GET', $url2);
        $content2 = $response2->getBody()->getContents();
        $contentArray2 = json_decode($content2, true);
        $data2 = $contentArray2['data'];

        $data = array_merge($data1, $data2);

        return view('barang.index', compact('data'));
    }

    public function store(Request $request)
    {
        // Periksa apakah barang_id sudah ada dalam tabel keranjang
        $existingKeranjang = Keranjang::where('barang_id', $request->barang_id)
            ->where('user_id', auth()->user()->id)
            ->first();

        // Jika barang_id sudah ada, tidak perlu menyimpan data baru
        if ($existingKeranjang) {
            return redirect('/barang');
        }

        // Jika barang_id belum ada, masukkan data ke tabel keranjang
        $keranjang = new Keranjang();
        $keranjang->barang_id = $request->barang_id;
        $keranjang->user_id = auth()->user()->id;
        $keranjang->save();

        return redirect('/barang');
    }

    public function showAll()
    {
        /* ============================================
            Parameter Sementara Kota & Provinsi
        ============================================ */
        $kota_user_id = 39;
        $provinsi_user_id = 5;

        $kota_asal = null;
        $provinsi_asal = null;

        $ongkir = [
            'city_destination' => $kota_user_id,
            'province_destination' => $provinsi_user_id,
            'weight' => 1000,
            'courier' => 'jne'
        ];
        $dataOngkirs = json_decode(json_encode($ongkir));

        $totalWeightTokoA = 0;
        $totalWeightTokoB = 0;
        $ongkirsDetails = [];

        // Mengambil daftar keranjang berdasarkan user ID
        $keranjangs = Keranjang::where('user_id', auth()->user()->id)->get();
        $client = new Client();
        $barangDetails = [];

        // Fungsi untuk mengambil data barang dari API dan menambahkannya ke array barangDetails
        function fetchBarangDetails($client, $url, &$barangDetails, $tokoName)
        {
            try {
                $response = $client->request('GET', $url);
                $content = $response->getBody()->getContents();
                $contentArray = json_decode($content, true);

                if (isset($contentArray['data'])) {
                    $barangData = $contentArray['data'];
                    $barangData['nama_toko'] = $tokoName; // Tambahkan nama toko ke data barang
                    $barangDetails[] = $barangData;
                }
            } catch (\Exception $e) {
                Log::error("Error fetching data from {$tokoName}: " . $e->getMessage());
            }
        }

        // Mengambil data barang dari API toko A
        foreach ($keranjangs as $item) {
            $url = "http://127.0.0.1:8001/api/barang/{$item->barang_id}";
            fetchBarangDetails($client, $url, $barangDetails, 'Toko A');
        }

        // Mengambil data barang dari API toko B
        foreach ($keranjangs as $item) {
            $url = "http://127.0.0.1:8002/api/barang/{$item->barang_id}";
            fetchBarangDetails($client, $url, $barangDetails, 'Toko B');
        }

        
        // get kota from barangDetails
        foreach ($barangDetails as $barang => $value) {
            $kota_asal_id = City::where('name', $value['kota'])->first()->city_id;
            $provinsi_asal_id = Province::where('name', $value['provinsi'])->first()->province_id;

            // add kota asal to barang details
            $barangDetails[$barang]['kota_asal'] = $kota_asal_id;
            $barangDetails[$barang]['provinsi_asal'] = $provinsi_asal_id;
            $barangDetails[$barang]['weight'] = $dataOngkirs->weight;
            $barangDetails[$barang]['courier'] = $dataOngkirs->courier;
            $barangDetails[$barang]['kota_tujuan'] = City::where('city_id', $kota_user_id)->pluck('name', 'city_id')->first();
            $barangDetails[$barang]['provinsi_tujuan'] = Province::where('province_id', $provinsi_user_id)->pluck('name', 'province_id')->first();
        }

        // check if nam_toko is Toko A or Toko B
        foreach ($barangDetails as $barang => $value) {
            if ($value['nama_toko'] == 'Toko A') {
                // calculate all barang weight from Toko A
                $totalWeightTokoA += $value['weight'];
            } else {
                // calculate all barang weight from Toko B
                $totalWeightTokoB += $value['weight'];
            }
        }

        // calculate ongkir from Toko A and Toko B based on total weight
        // foreach ($barangDetails as $barang => $value) {
        //     if ($value['nama_toko'] == 'Toko A') {
        //         $costA = RajaOngkir::ongkosKirim([
        //             'origin'        => $value['kota_asal'], // ID kota/kabupaten asal
        //             'destination'   => $kota_user_id, // ID kota/kabupaten tujuan
        //             'weight'        => $totalWeightTokoA, // berat barang dalam gram
        //             'courier'       => $dataOngkirs->courier // kode kurir pengiriman: ['jne', 'tiki', 'pos'] untuk starter
        //         ])->get();

        //         $ongkirsDetails[] = $costA;
        //     } else {
        //         $costB = RajaOngkir::ongkosKirim([
        //             'origin'        => $value['kota_asal'], // ID kota/kabupaten asal
        //             'destination'   => $kota_user_id, // ID kota/kabupaten tujuan
        //             'weight'        => $totalWeightTokoB, // berat barang dalam gram
        //             'courier'       => $dataOngkirs->courier // kode kurir pengiriman: ['jne', 'tiki', 'pos'] untuk starter
        //         ])->get();

        //         $ongkirsDetails[] = $costB;
        //     }
        // }

        // get kota asal
        foreach ($barangDetails as $barang => $value) {
            $kota_asal = City::where('city_id', $value['kota_asal'])->pluck('name', 'city_id')->first();
        }

        $costA = RajaOngkir::ongkosKirim([
            'origin'        => $kota_asal_id, // ID kota/kabupaten asal
            'destination'   => $kota_user_id, // ID kota/kabupaten tujuan
            'weight'        => $totalWeightTokoA, // berat barang dalam gram
            'courier'       => $dataOngkirs->courier // kode kurir pengiriman: ['jne', 'tiki', 'pos'] untuk starter
        ])->get();

        $ongkirsDetails[] = $costA;

        $costB = RajaOngkir::ongkosKirim([
            'origin'        => $kota_asal_id, // ID kota/kabupaten asal
            'destination'   => $kota_user_id, // ID kota/kabupaten tujuan
            'weight'        => $totalWeightTokoB, // berat barang dalam gram
            'courier'       => $dataOngkirs->courier // kode kurir pengiriman: ['jne', 'tiki', 'pos'] untuk starter
        ])->get();

        $ongkirsDetails[] = $costB;

        // total ongkir a dan ongkir
        // $totalOngkirA = $costA[0][0]['costs'][0]['value'];
        // $totalOngkirB = $costB[0][0]['costs'][0]['value'];

        // // input ke database transaksis
        // $transaksi = new Transaksi;
        // $transaksi->user_id = Auth::user()->id;
        
        // get unique toko name
        $tokoNames = array_unique(array_column($barangDetails, 'nama_toko'));
        $tokoNames = array_values($tokoNames);
        // dd($barangDetails);

        // Mengembalikan tampilan dengan data barang yang diambil dari kedua API
        return view('barang.keranjang', compact('barangDetails', 'ongkirsDetails', 'tokoNames'));
    }

}