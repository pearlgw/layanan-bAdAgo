<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Obat;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // get all data obat from table obat
        $obats = Barang::all();
        $users = User::all();

        foreach ($obats as $obat) {
            $obat->kode_barang = $obat->kode_barang;
            $obat->nama_barang= $obat->nama_barang;
            $obat->stok = $obat->stok;
            $obat->nama_toko = $obat->nama_toko;
            $obat->kota = $obat->kota;
            $obat->weight = $obat->weight;
            $obat->provinsi = $obat->provinsi;

            $obat->harga_jual = number_format($obat->harga_jual, 0, ',', '.');
        }

        return view('home', compact('obats', 'users'));
    }

    public function edit_obat($id)
    {
        $obat = Barang::find($id);

        return view('edit_obat', compact('obat'));
    }

    public function update_obat(Request $request, Barang $obat)
    {
        // without validation
        $obat->update($request->all());

        return redirect()->route('obat_edit', $obat->id)->with('success', 'Data obat berhasil diubah');
    }

    public function destroy_obat($id)
    {
        $obat = Barang::find($id);
        $obat->delete();

        return redirect()->route('home')->with('success', 'Data obat berhasil dihapus');
    }

    public function store(Request $request)
    {
        // $request->nama_toko = 'Apotek Sehat';
        // Barang::create($request->all());
        $obat = new Barang();

        $obat->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $obat->kode_barang = $request->kode_barang;
        $obat->nama_barang = $request->nama_barang;
        $obat->stok = $request->stok;
        $obat->nama_toko = $request->nama_toko;
        $obat->kota = $request->kota;
        $obat->weight = $request->weight;
        $obat->provinsi = $request->provinsi;
        $obat->harga_jual = $request->harga_jual;
        $obat->save();

        return redirect()->route('home')->with('success', 'Data obat berhasil ditambahkan');
    }

    public function create_obat_form()
    {
        return view('add_obat');
    }
}