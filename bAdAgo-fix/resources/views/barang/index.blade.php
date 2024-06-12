@extends('layouts.main')

@section('contents')
    <div class="container">
        @if (auth()->check())
            {{-- <form action="/logout" method="post">
                @csrf
                <button type="submit" class="btn btn-primary">Logout</button>
            </form> --}}

            {{-- <a href="/keranjang">Lihat Keranjang</a> --}}
        @endif



        <div class="row">
            @foreach ($data as $item)

            <div class="w-full max-w-xs bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 m-4">
                <a href="#">
                    <img class="py-4 px-2 rounded-t-lg" src="https://images.unsplash.com/photo-1593642532973-d31b6557fa68?ixlib=rb-1.2.1&auto=format&fit=crop&w=750&q=80" alt="product image" />
                </a>

                <div class="px-2 pb-4">
                    <a href="#">
                        <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $item['nama_barang'] }}</h5>
                    </a>
                    <div class="flex items-center mt-2.5 mb-2">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 me-2">Stok: {{ $item['stok'] }}</span>
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 ">Toko: {{ $item['nama_toko'] }}</span>
                    </div>

                    <div class="items-center mt-2.5 mb-2">
                        <span class=" text-gray-900 text-xl font-bold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-white ">Rp.{{ $item['harga_jual'] }}</span>
                    </div>


                    <div class="flex items-center justify-between">
                        <a href="#" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 " data-bs-toggle="modal"
                        data-bs-target="#detailModal-{{ $item['id'] }}">
                            Detail
                        </a>

                    @if (auth()->check())
                        <form action="/keranjang" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="barang_id" value="{{ $item['id'] }}">
                            <button type="submit" class="text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                                Add to cart
                            </button>
                        </form>
                    @else
                        <a href="/login" class="text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-800">
                            Add to cart
                        </a>
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
