@extends('layouts.main')

@section('contents')
    <div class="container">
        <h2>Selamat Datang Di Toko Kami</h2>
        @if (auth()->check())
            <form action="/logout" method="post">
                @csrf
                <button type="submit" class="btn btn-primary">Logout</button>
            </form>

            <a href="/keranjang">Lihat Keranjang</a>
        @endif

        <h1>Kumpulan Barang</h1>


        <div class="row">
            @foreach ($data as $item)
                <div class="col-md-4 my-3">
                    <div class="card" style="width: 18rem;">
                        <img src="..." class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item['nama_barang'] }}</h5>
                            <p class="card-text">Stok: {{ $item['stok'] }}, toko {{ $item['nama_toko'] }}, harga
                                {{ $item['harga_jual'] }}</p>
                            <!-- Tombol Detail sekarang membuka modal -->
                            <a href="" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#detailModal-{{ $item['id'] }}">Detail</a>
                            @if (auth()->check())
                                <form action="/keranjang" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="barang_id" value="{{ $item['id'] }}">
                                    <button type="submit" class="btn btn-primary">Masukkan Keranjang</button>
                                </form>
                            @else
                                <a href="/login" class="btn btn-primary">Masukkan Keranjang</a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Modal untuk setiap item -->
                <div class="modal fade" id="detailModal-{{ $item['id'] }}" tabindex="-1"
                    aria-labelledby="detailModalLabel-{{ $item['id'] }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailModalLabel-{{ $item['id'] }}">Detail
                                    {{ $item['nama_barang'] }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Kode Barang: {{ $item['kode_barang'] }}</p>
                                <p>Nama Barang: {{ $item['nama_barang'] }}</p>
                                <p>Stok: {{ $item['stok'] }}</p>
                                <p>Toko: {{ $item['nama_toko'] }}</p>
                                <p>Harga: {{ $item['harga_jual'] }}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                @if (auth()->check())
                                    <form action="/keranjang" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="barang_id" value="{{ $item['id'] }}">
                                        <button type="submit" class="btn btn-primary">Masukkan Keranjang</button>
                                    </form>
                                @else
                                    <a href="/login" class="btn btn-primary">Masukkan Keranjang</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
