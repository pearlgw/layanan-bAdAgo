<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Keranjang;
use App\Models\Transaksi;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        return redirect('/keranjang');
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
}
