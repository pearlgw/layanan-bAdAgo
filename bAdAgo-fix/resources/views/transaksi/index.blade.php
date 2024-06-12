@extends('layouts.main')

@section('contents')




<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    No
                </th>
                <th scope="col" class=" py-3">
                    Kode Transakasi
                </th>
                <th scope="col" class=" py-3">
                    Total Transaksi
                </th>
                <th scope="col" class=" py-3">
                    Nama
                </th>
                <th scope="col" class=" py-3">
                    Provinsi
                </th>
                <th scope="col" class=" py-3">
                    Kota
                </th>
                <th scope="col" class=" py-3">
                    Barang
                </th>
                <th scope="col" class=" py-3">
                    Berat
                </th>
                <th scope="col" class=" py-3">
                    Jumlah
                </th>
                <th scope="col" class=" py-3">
                    Total/Item
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi as $item)
                @php
                    $tokoDisplayed = [];
                    $totalWeightA = 0;
                    $totalWeightB = 0;
                    $totalBayar = 0;
                @endphp
                @foreach ($item->detailTransaksi as $index => $detail)
                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">

                    @if ($index == 0)

                        <th rowspan="{{ $item->detailTransaksi->count() }}" scope="row" class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $loop->iteration }}

                        </th>
                        <td rowspan="{{ $item->detailTransaksi->count() }}" class=" py-4">
                            {{ $item->kode_transaksi }}
                        </td>
                        <td rowspan="{{ $item->detailTransaksi->count() }}" class=" py-4">
                            {{ $item->total_transaksi }}
                        </td>
                        <td rowspan="{{ $item->detailTransaksi->count() }}" class=" py-4">
                            {{ $item->user->name }}
                        </td>
                        <td rowspan="{{ $item->detailTransaksi->count() }}" class=" py-4">
                            {{ auth()->user()->provinsi }}
                        </td>
                        <td rowspan="{{ $item->detailTransaksi->count() }}" class=" py-4">
                            {{ auth()->user()->kota }}
                        </td>
                    @endif
                    <td  class=" py-4">
                        {{ $detail->nama_barang }}
                    </td>
                    <td  class=" py-4">
                        {{ $detail->weight }} gram
                    </td>
                    <td  class=" py-4">
                        {{ $detail->qty }}
                    </td>
                    <td  class=" py-4">
                        {{ $detail->total_per_item }}
                    </td>
                    @php
                        $totalBayar += $detail->total_per_item;
                        $totalWeightA += $detail->weight;
                        $totalWeightB += $detail->weight * $detail->qty;
                    @endphp


                </tr>
                @endforeach


            @endforeach

        </tbody>
    </table>
</div>






    {{-- <h1>Halaman Checkout</h1>
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
                        $totalWeightA = 0;
                        $totalWeightB = 0;
                        $totalBayar = 0;
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
                            <td>{{ $detail->weight }} gram</td>
                            <td>{{ $detail->qty }}</td>
                            <td>{{ $detail->total_per_item }}</td>
                            @php
                                $totalBayar += $detail->total_per_item;
                                $totalWeightA += $detail->weight;
                                $totalWeightB += $detail->weight * $detail->qty;
                            @endphp
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table> --}}

        <h3>Detail Ongkir</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Nama Toko</th>
                    <th scope="col">Total Berat</th>
                    <th scope="col">Kurir</th>
                    <th scope="col">Service</th>
                    <th scope="col">Estimasi</th>
                    <th scope="col">Ongkir</th>
                </tr>

                {{-- get unique $detail->toko --}}
                @foreach ($transaksi as $item)
                    @foreach ($item->detailTransaksi as $index => $detail)
                        @if (!in_array($detail->toko, $tokoDisplayed))
                            @php
                                $tokoDisplayed[] = $detail->toko;
                                $totalBerat = $detail->weight * $detail->qty;
                            @endphp
                        @endif
                    @endforeach
                @endforeach

                @php
                    $totalOngkir = 0;
                    $tokoDisplayed = [];
                    $totalBayar = 0;
                @endphp

                @foreach ($tokoDisplayed as $index => $tokoName)
                    @foreach ($ongkirsDetails[$index] as $ongkir)
                        @php
                            $totalOngkir += $ongkir['costs'][0]['cost'][0]['value'];
                        @endphp
                    @endforeach
                @endforeach

                {{-- for each $tokoDisplayed --}}
                @foreach ($tokoDisplayed as $index => $tokoName)
                    @foreach ($ongkirsDetails[$index] as $ongkir)
                        <tr>
                            <td>
                                <p>{{ $tokoName }}</p>
                            </td>
                            @if ($tokoName == 'Toko A')
                                @php
                                    $totalBerat = $totalWeightA;
                                @endphp
                            @else
                                @php
                                    $totalBerat = $totalWeightB;
                                @endphp
                            @endif

                            <td>
                                <input type="text" name="total_berat" id="total_berat" class="form-control"
                                    value="{{ $totalBerat }}" readonly>
                            </td>
                            <td>
                                <input type="text" name="kurir" id="kurir" class="form-control"
                                    value="{{ $ongkir['code'] }}" readonly>
                            </td>
                            <td>
                                <input type="text" name="service" id="service" class="form-control"
                                    value="{{ $ongkir['costs'][0]['service'] }}" readonly>
                            </td>
                            <td>
                                <input type="text" name="estimasi" id="estimasi" class="form-control"
                                    value="{{ $ongkir['costs'][0]['cost'][0]['etd'] }}" readonly>
                            </td>
                            <td>
                                <input type="text" name="ongkir" id="ongkir" class="form-control"
                                    value="{{ $ongkir['costs'][0]['cost'][0]['value'] }}" readonly>
                            </td>
                        </tr>
                    @endforeach
                @endforeach

                <tr>
                    <td colspan="5">
                        <p>Total Ongkir</p>
                    </td>
                    <td>
                        <form action="/checked-total-ongkir" method="POST">
                            @csrf
                            {{-- genrate get total harga ongkir toko A dan B menambahkannya --}}
                            <input type="text" name="total_ongkir" id="total_ongkir" class="form-control"
                                value="{{ $totalOngkir }}" readonly>
                            <button type="submit">Checked</button>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td colspan="5">
                        <p>Total yang perlu dibayarkan</p>
                    </td>
                    <td>
                        <form action="/checked-total-keseluruhan" method="POST">
                            @csrf
                            {{-- genrate get total harga ongkir toko A dan B menambahkannya --}}
                            <input type="text" name="total_keseluruhan" id="total_keseluruhan" class="form-control"
                                value="{{ $totalOngkir + $totalBayar }}" readonly>
                            <button type="submit">Checked</button>
                        </form>
                    </td>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <button class="btn btn-primary" id="pay-button">Bayar Sekarang</button>
    </div>

    @if (session('snapToken'))
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#pay-button').click(function() {
                    // Iterasi melalui setiap objek transaksi
                    @foreach ($transaksi as $trx)
                        var transaksiId = '{{ $trx->id }}'; // Mendapatkan ID transaksi dari PHP
                        $.ajax({
                            url: '/update-status-paid/' +
                                transaksiId, // URL endpoint untuk mengupdate status transaksi
                            type: 'PUT', // Menggunakan metode PUT untuk update
                            data: {
                                _token: '{{ csrf_token() }}', // Menambahkan CSRF token
                            },
                            success: function(response) {
                                // Setelah status berhasil diupdate, lakukan pembayaran
                                window.snap.pay('{{ session('snapToken') }}', {
                                    onSuccess: function(result) {
                                        alert("Payment success!");
                                        console.log(result);
                                    },
                                    onPending: function(result) {
                                        alert("Waiting for your payment!");
                                        console.log(result);
                                    },
                                    onError: function(result) {
                                        alert("Payment failed!");
                                        console.log(result);
                                    },
                                    onClose: function() {
                                        // Callback function when the popup is closed
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                // Handle jika permintaan gagal
                                alert('Gagal mengupdate status transaksi. Silakan coba lagi.');
                            }
                        });
                    @endforeach
                });
            });
        </script>
    @endif
@endsection
