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
                        $totalBayar = 0;
                        $totalWeightA = 0;
                        $totalWeightB = 0;
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
        </table>

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
                            @if ( $tokoName == 'Toko A')
                                @php
                                    $totalBerat = $totalWeightA;
                                @endphp
                            @else
                                @php
                                    $totalBerat = $totalWeightB;
                                @endphp
                            @endif

                            <td>
                                <input type="text" name="total_berat" id="total_berat" class="form-control" value="{{ $totalBerat }}" readonly>
                            </td>
                            <td>
                                <input type="text" name="kurir" id="kurir" class="form-control" value="{{ $ongkir['code'] }}" readonly>
                            </td>
                            <td>
                                <input type="text" name="service" id="service" class="form-control" value="{{ $ongkir['costs'][0]['service'] }}" readonly>
                            </td>
                            <td>
                                <input type="text" name="estimasi" id="estimasi" class="form-control" value="{{ $ongkir['costs'][0]['cost'][0]['etd'] }}" readonly>
                            </td>
                            <td>
                                <input type="text" name="ongkir" id="ongkir" class="form-control" value="{{ $ongkir['costs'][0]['cost'][0]['value'] }}" readonly>
                            </td>
                        </tr>
                    @endforeach
                @endforeach

                <tr>
                    <td colspan="5">
                        <p>Total Ongkir</p>
                    </td>
                    <td>
                        {{-- genrate get total harga ongkir toko A dan B menambahkannya --}}
                        <input type="text" name="total_ongkir" id="total_ongkir" class="form-control" value="{{ $totalOngkir }}" readonly>
                    </td>
                </tr>
                <tr>
                    <td colspan="5">
                        <p>Total yang perlu dibayarkan</p>
                    </td>
                    <td>
                        {{-- genrate get total harga ongkir toko A dan B menambahkannya --}}
                        <input type="text" name="total_ongkir" id="total_ongkir" class="form-control" value="{{ $totalOngkir + $totalBayar }}" readonly>
                    </td>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
@endsection
