<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\DetailTransaksi;
use App\Models\Keranjang;
use App\Models\Transaksi;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Kavist\RajaOngkir\Facades\RajaOngkir;

class TransaksiController extends Controller
{
    public function checkout(Request $request)
    {
        // Membuat kode transaksi otomatis
        $kodeTransaksi = 'TRX-' . time();

        // Mendapatkan user_id dari user yang sedang login
        $userId = Auth::id();

        // Membuat transaksi baru
        $transaksi = Transaksi::create([
            'kode_transaksi' => $kodeTransaksi,
            'user_id' => $userId,
            'total_ongkir' => 0,
            'total_transaksi' => $request->total_transaksi,
        ]);

        // Menyimpan detail transaksi
        foreach ($request->barang_id as $index => $barangId) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'barang_id' => $barangId,
                'qty' => $request->qty[$index],
                'total_per_item' => $request->total_per_item[$index],
            ]);

            // Persiapkan data untuk dikirim ke API
            $data = [
                'stok' => $request->qty[$index]
            ];

            // Buat instance dari Guzzle Client
            $client = new Client();

            // Cek dan update stok di API
            $this->updateStock($client, $barangId, $data);
        }

        // Kurangi stok di API
        Keranjang::where('user_id', $userId)->whereIn('barang_id', $request->barang_id)->delete();

        // Redirect ke halaman sukses atau halaman yang diinginkan
        return redirect('/checkout');
    }

    private function updateStock(Client $client, $barangId, $data)
    {
        $isUpdated = false;

        // Cek di API 8001
        try {
            $response = $client->request('GET', "http://127.0.0.1:8001/api/barang/{$barangId}");
            $responseData = json_decode($response->getBody(), true);

            if ($responseData['status'] == 'true') {
                // Jika barang ditemukan, lakukan pengurangan stok
                $client->request('PATCH', "http://127.0.0.1:8001/api/barang/{$barangId}", [
                    'headers' => ['Content-Type' => 'application/json'],
                    'json' => $data
                ]);
                $isUpdated = true;
            }
        } catch (RequestException $e) {
            Log::error("Failed to check or update stock for barang_id {$barangId} on API 8001: " . $e->getMessage());
        }

        // Jika barang tidak ditemukan di API 8001, cek di API 8002
        if (!$isUpdated) {
            try {
                $response = $client->request('GET', "http://127.0.0.1:8002/api/barang/{$barangId}");
                $responseData = json_decode($response->getBody(), true);

                if ($responseData['status'] == 'true') {
                    // Jika barang ditemukan, lakukan pengurangan stok
                    $client->request('PATCH', "http://127.0.0.1:8002/api/barang/{$barangId}", [
                        'headers' => ['Content-Type' => 'application/json'],
                        'json' => $data
                    ]);
                }
            } catch (RequestException $e) {
                Log::error("Failed to check or update stock for barang_id {$barangId} on API 8002: " . $e->getMessage());
            }
        }
    }

    public function hapusBarang(string $id)
    {
        Keranjang::where('user_id', Auth::id())->where('barang_id', $id)->delete();

        return redirect('/keranjang');
    }

    public function showTransaksi()
    {
        $transaksi = Transaksi::where('user_id', Auth::id())
            ->where('status', 'unpaid')
            ->with('detailTransaksi')
            ->get();
        // dd($transaksi);

        $client = new Client();

        // Fungsi untuk mengambil detail barang dari API
        function fetchBarangDetails($client, $url)
        {
            try {
                $response = $client->request('GET', $url);
                $content = $response->getBody()->getContents();
                $contentArray = json_decode($content, true);

                if (isset($contentArray['data'])) {
                    return $contentArray['data'];
                }
            } catch (\Exception $e) {
                Log::error("Error fetching data from {$url}: " . $e->getMessage());
            }
            return null;
        }

        $total_weight_A = 0;
        $total_weight_B = 0;
        $destinationA = null;
        $destinationB = null;
        $ongkirsDetails = [];

        foreach ($transaksi as $item) {
            $tokoData = [];

            foreach ($item->detailTransaksi as $detail) {
                // Mengambil data barang dari API toko A
                $urlA = "http://127.0.0.1:8001/api/barang/{$detail->barang_id}";
                $dataA = fetchBarangDetails($client, $urlA);

                // Mengambil data barang dari API toko B
                $urlB = "http://127.0.0.1:8002/api/barang/{$detail->barang_id}";
                $dataB = fetchBarangDetails($client, $urlB);

                // Memprioritaskan data barang dari API toko A jika ada, jika tidak, menggunakan data barang dari API toko B
                if ($dataA) {
                    $detail->nama_barang = $dataA['nama_barang'];
                    $detail->weight = $dataA['weight'];
                    $detail->toko = 'Toko A';

                    $total_weight_A += $dataA['weight'];
                    $detail->total_weight_A = $total_weight_A;

                    $destinationA = City::where('name', $dataA['kota'])->first()->city_id;
                } elseif ($dataB) {
                    $detail->nama_barang = $dataB['nama_barang'];
                    $detail->weight = $dataB['weight'];
                    $detail->toko = 'Toko B';

                    $total_weight_B += $dataB['weight'];
                    $detail->total_weight_B = $total_weight_B;

                    $destinationB = City::where('name', $dataB['kota'])->first()->city_id;
                } else {
                    $detail->nama_barang = 'Barang tidak ditemukan';
                }
            }

            $costA = RajaOngkir::ongkosKirim([
                'origin'        => $destinationA, // ID kota/kabupaten asal
                'destination'   => City::where('name', auth()->user()->kota)->first()->city_id, // ID kota/kabupaten tujuan
                'weight'        => $total_weight_A, // berat barang dalam gram
                'courier'       => 'jne' // kode kurir pengiriman: ['jne', 'tiki', 'pos'] untuk starter
            ])->get();

            $costB = RajaOngkir::ongkosKirim([
                'origin'        => $destinationB, // ID kota/kabupaten asal
                'destination'   => City::where('name', auth()->user()->kota)->first()->city_id, // ID kota/kabupaten tujuan
                'weight'        => $total_weight_B, // berat barang dalam gram
                'courier'       => 'jne' // kode kurir pengiriman: ['jne', 'tiki', 'pos'] untuk starter
            ])->get();

            $ongkirsDetails[] = $costA;
            $ongkirsDetails[] = $costB;

            // Menyimpan data tokoData ke transaksi
            // $item->tokoData = $tokoData;
        }


        return view('transaksi.index', compact('transaksi', 'ongkirsDetails'));
    }

    public function checkedTotalKeseluruhan(Request $request)
    {
        $totalKeseluruhan = Transaksi::where('status', 'unpaid')->first();

        $totalKeseluruhan->update([
            'total_keseluruhan' => $request->total_keseluruhan,
        ]);

        // Set konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Parameter untuk Snap API
        $params = [
            'transaction_details' => [
                'order_id' => $totalKeseluruhan->kode_transaksi,
                'gross_amount' => $totalKeseluruhan->total_keseluruhan,
            ],
            'customer_details' => [
                'first_name' => $totalKeseluruhan->user->name,
                'last_name' => '',
                'phone' => $totalKeseluruhan->user->no_telp,
            ],
        ];

        // Mendapatkan Snap Token
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        // dd($snapToken);

        return redirect('/checkout')->with([
            'snapToken' => $snapToken,
            'transaksi' => $totalKeseluruhan,
        ]);
    }

    public function checkedTotalOngkir(Request $request)
    {
        $totalOngkir = Transaksi::where('status', 'unpaid')->first();

        $totalOngkir->update([
            'total_ongkir' => $request->total_ongkir
        ]);

        return redirect('/checkout');
    }

    public function updateStatusPaid($transaksiId)
    {
        // Temukan transaksi berdasarkan ID
        $transaksi = Transaksi::findOrFail($transaksiId);

        // Update status transaksi menjadi "paid"
        $transaksi->status = 'paid';
        $transaksi->save();

        return response()->json(['message' => 'Status transaksi berhasil diupdate.'], 200);
    }
}
