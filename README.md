cara menggunakannya:
1. lakukan git clone pada branch master (ui)
2. buka projeknya satu per satu bagian folder bAdAgo-fix, toko-a, dan toko-b
   - lakukan "composer install" ,
   - kemudian copas dulu .env example menjadi .env dan isi konfigurasinya di dalam .env
        - midtrans
           MIDTRANS_MERCHANT_ID=G928554474
           MIDTRANS_CLIENT_KEY=SB-Mid-client-MdnVfI1XLFALVr5f
           MIDTRANS_SERVER_KEY=SB-Mid-server-XqHhRaIuKtZWhL-QmyGREhGn
        
        - rajaOngkir
           RAJAONGKIR_API_KEY=30c8bbf5aa395e0b1f2489eace4fcfa4 (untuk api key dari website (raja ongkir) harus bikin akun dulu dan copas api key dari masing masing akun)
           RAJAONGKIR_PACKAGE=starter

   - kemudian lakukan perintah "php artisan key:generate"
   - lakukan "php artisan migrate" dan kemudian lakukan "php artisan db:seed"
