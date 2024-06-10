<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class ApiBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Barang::all();
        return response()->json([
            'status' => 'true',
            'message' => 'data ditemukan',
            'data' => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $dataBarang = new Barang();
        // $dataBarang->id = Uuid::uuid4()->toString();
        // $dataBarang->kode_barang = $request->kode_barang;
        // $dataBarang->nama_barang = $request->nama_barang;
        // $dataBarang->stok = $request->stok;
        // $dataBarang->harga_jual = $request->harga_jual;

        // $dataBarang->save();

        // return response()->json([
        //     'status' => true,
        //     'message' => 'data sukses di masukkan'
        // ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Barang::find($id);
        if ($data) {
            return response()->json([
                'status' => 'true',
                'message' => 'data ditemukan',
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'status' => 'false',
                'message' => 'data tidak ditemukan',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dataBarang = Barang::find($id);
        $dataBarang->stok -= $request->stok;

        $dataBarang->save();

        return response()->json([
            'status' => true,
            'message' => 'sukses stok berkurang'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $dataBarang = Barang::find($id);

        // $dataBarang->delete();

        // return response()->json([
        //     'status' => true,
        //     'message' => 'data sukses di hapus'
        // ], 200);
    }
}