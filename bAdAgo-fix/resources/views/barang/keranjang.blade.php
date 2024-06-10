@extends('layouts.main')

@section('contents')
    Halaman Keranjang
    <a href="/barang">Kembali</a>

    <form action="/checkout" method="POST" class="d-inline">
        @csrf
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Kode Barang</th>
                    <th scope="col">Nama Barang</th>
                    <th scope="col">Stok</th>
                    <th scope="col">Toko</th>
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
                        <th>{{ $loop->iteration }}</th>
                        <td>{{ $barang['kode_barang'] }}</td>
                        <td>{{ $barang['nama_barang'] }}</td>
                        <td>{{ $barang['stok'] }}</td>
                        <td>{{ $barang['nama_toko'] }}</td>
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
    </form>

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
