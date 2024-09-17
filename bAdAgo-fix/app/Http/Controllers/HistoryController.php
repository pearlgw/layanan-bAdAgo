<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user()->id;
        $transaksiId = Transaksi::where('user_id', $user)->value('id');
        $barangIds = DetailTransaksi::where('transaksi_id', $transaksiId)->pluck('barang_id')->toArray();

        $client = new \GuzzleHttp\Client();

        $urlTokoA = "http://127.0.0.1:8001/api/barang/" . implode(',', $barangIds);

        try {
            $responseTokoA = $client->request('GET', $urlTokoA);
            $dataTokoA = json_decode($responseTokoA->getBody()->getContents(), true);
        } catch (\Exception $e) {
            // Handle error jika request ke Toko A gagal
            echo "Error fetching data from Toko A: " . $e->getMessage();
            $dataTokoA = []; // Atau sesuaikan dengan error handling yang sesuai
        }

        $urlTokoB = "http://127.0.0.1:8002/api/barang/" . implode(',', $barangIds);

        try {
            $responseTokoB = $client->request('GET', $urlTokoB);
            $dataTokoB = json_decode($responseTokoB->getBody()->getContents(), true);
        } catch (\Exception $e) {
            // Handle error jika request ke Toko B gagal
            echo "Error fetching data from Toko B: " . $e->getMessage();
            $dataTokoB = []; // Atau sesuaikan dengan error handling yang sesuai
        }

        $combinedData = [
            'Toko A' => $dataTokoA,
            'Toko B' => $dataTokoB,
        ];

        dd($combinedData);







        return view('history.index');


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
