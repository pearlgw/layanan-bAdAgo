@extends('layouts.main')

@section('contents')
    <h1>Halaman Checkout</h1>
    <div class="container-fluid">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Kode Transaksi</th>
                    <th scope="col">Total Transaksi</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Provinsi User</th>
                    <th scope="col">Kota User</th>
                    <th scope="col">Barang</th>
                    <th scope="col">Berat</th>
                    <th scope="col">Jumlah Pembelian</th>
                    <th scope="col">Total / Item</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi as $item)
                    @php
                        $tokoDisplayed = [];
                    @endphp
                    @foreach ($item->detailTransaksi as $index => $detail)
                        <tr>
                            @if ($index == 0)
                                <td rowspan="{{ $item->detailTransaksi->count() }}">{{ $loop->iteration }}</td>
                                <td rowspan="{{ $item->detailTransaksi->count() }}">{{ $item->kode_transaksi }}</td>
                                <td rowspan="{{ $item->detailTransaksi->count() }}">{{ $item->total_transaksi }}</td>
                                <td rowspan="{{ $item->detailTransaksi->count() }}">{{ $item->user->name }}</td>
                                <td rowspan="{{ $item->detailTransaksi->count() }}">{{ auth()->user()->provinsi }}</td>
                                <td rowspan="{{ $item->detailTransaksi->count() }}">
                                    {{ auth()->user()->kota }}
                                </td>
                            @endif
                            <td>{{ $detail->nama_barang }}</td>
                            <td>{{ $detail->weight }} kg</td>
                            <td>{{ $detail->qty }}</td>
                            <td>{{ $detail->total_per_item }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
