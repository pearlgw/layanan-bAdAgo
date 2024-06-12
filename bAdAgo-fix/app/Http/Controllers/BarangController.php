<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        return redirect('/keranjang');
    }

    public function showAll()
    {
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

        // Mengembalikan tampilan dengan data barang yang diambil dari kedua API
        return view('barang.keranjang', compact('barangDetails'));
    }

}
