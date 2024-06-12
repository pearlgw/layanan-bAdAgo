@extends('layouts.main')

@section('contents')




<form action="/checkout" method="POST">
    @csrf
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3">
                        KB
                    </th>
                    <th scope="col" class="px-6 py-3">
                        NB
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Berat
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Stok
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Toko
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Provinsi
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Kota
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Harga
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Jumlah Beli
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Total
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangDetails as $index => $barang)

                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <input type="hidden" name="barang_id[]" value="{{ $barang['id'] }}">
                        <input type="hidden" name="weight[]" value="{{ $barang['weight'] }}">
                    <td class="px-6 py-4">
                        {{$loop->iteration}}
                    </td>
                    <td class="px-6 py-4">
                        {{ $barang['kode_barang'] }}
                    </td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $barang['nama_barang'] }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $barang['weight'] }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $barang['stok'] }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $barang['nama_toko'] }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $barang['provinsi'] }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $barang['kota'] }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $barang['harga_jual'] }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline" onclick="hapusBarang('{{ $barang['id'] }}'); return false;">Hapus</a>
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" name="qty[]" class="qtyInput" data-harga="{{ $barang['harga_jual'] }}">
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" readonly name="total_per_item[]" class="totalPerItem">                </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mt-4">

                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Total Bayar</h5>
                <input type="text" id="total_transaksi" name="total_transaksi"  class="font-bold">

                <button type="submit" class="mt-2 inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Checkout
                    <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                    </svg>
                </button>
            </div>
</form>


    {{-- <form action="/checkout" method="POST" class="d-inline">
        @csrf
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Kode Barang</th>
                    <th scope="col">Nama Barang</th>
                    <th scope="col">Berat (Gram)</th>
                    <th scope="col">Stok</th>
                    <th scope="col">Toko</th>
                    <th scope="col">Provinsi Asal</th>
                    <th scope="col">Kota Asal</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Aksi</th>
                    <th scope="col">Jumlah Pembelian</th>
                    <th scope="col">Total Item</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangDetails as $index => $barang)
                    <tr>
                        <input type="hidden" name="barang_id[]" value="{{ $barang['id'] }}">
                        <input type="hidden" name="weight[]" value="{{ $barang['weight'] }}">
                        <th>{{ $loop->iteration }}</th>
                        <td>{{ $barang['kode_barang'] }}</td>
                        <td>{{ $barang['nama_barang'] }}</td>
                        <td>{{ $barang['weight'] }}</td>
                        <td>{{ $barang['stok'] }}</td>
                        <td>{{ $barang['nama_toko'] }}</td>
                        <td>{{ $barang['provinsi'] }}</td>
                        <td>{{ $barang['kota'] }}</td>
                        <td>{{ $barang['harga_jual'] }}</td>
                        <td>
                            <a href="#" onclick="hapusBarang('{{ $barang['id'] }}'); return false;">Hapus</a>
                        </td>

                        <td>
                            <input type="number" name="qty[]" class="qtyInput" data-harga="{{ $barang['harga_jual'] }}">
                        </td>
                        <td>
                            <input type="number" readonly name="total_per_item[]" class="totalPerItem">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        Total Transaksi : <input type="text" id="total_transaksi" name="total_transaksi" readonly><br>
        <button type="submit" class="btn btn-primary">Check Out</button>
    </form> --}}

    <script>
        const qtyInputs = document.querySelectorAll('.qtyInput');
        const totalPerItems = document.querySelectorAll('.totalPerItem');

        qtyInputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                const hargaJual = parseFloat(input.getAttribute('data-harga'));
                const qty = parseInt(input.value);
                const total = hargaJual * qty;
                totalPerItems[index].value = total;

                let totalTransaksi = 0;
                totalPerItems.forEach(item => {
                    totalTransaksi += parseFloat(item.value) || 0;
                });
                document.getElementById('total_transaksi').value = totalTransaksi;
            });
        });

        function hapusBarang(id) {
            if (confirm("Apakah Anda yakin ingin menghapus barang ini?")) {
                fetch(`/delete/barang/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (response.ok) {
                        // Pembaruan halaman setelah berhasil menghapus barang
                        window.location.reload();
                    } else {
                        throw new Error('Network response was not ok.');
                    }
                }).catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });
            }
        }
    </script>
@endsection
