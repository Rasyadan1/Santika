<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Santika
    |--------------------------------------------------------------------------
    |
    | Konfigurasi khusus untuk aplikasi Santika (Sewa Alat Santet dan Pelet
    | Seketika). Semua variabel bisnis diatur di sini agar mudah dikelola.
    |
    */

    // Denda tetap per hari keterlambatan (dalam Rupiah), terpisah dari tarif sewa unit
    'denda_per_hari' => (int) env('DENDA_PER_HARI', 50000),

    // Batas maksimal unit yang dapat dipinjam bersamaan oleh satu anggota
    'batas_pinjam' => (int) env('BATAS_PINJAM', 2),

    // Durasi pinjam dalam hari (tanggal_jatuh_tempo = tanggal_pinjam + durasi_pinjam)
    'durasi_pinjam' => (int) env('DURASI_PINJAM', 5),

    // Password akun demo untuk penggunaan lokal dan pengujian saja
    'demo_password' => env('SANTIKA_DEMO_PASSWORD', 'password'),

];
