<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class ApiBarangController extends Controller
{
    public function index()
    {
        $data = Barang::all();
        return response()->json([
            'status' => 'true',
            'message' => 'data ditemukan',
            'data' => $data
        ], 200);
    }

    public function show(string $id)
    {
        $data = Barang::find($id);
        if ($data) {
            return response()->json([
                'status' => 'true',
                'message' => 'data ditemukan',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'data tidak ditemukan',
            ], 404);
        }
    }
    
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
}