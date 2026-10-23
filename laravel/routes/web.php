<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('/katalog', function () {
    // Data dummy sementara untuk preview Frontend
    $units = [
        (object)[
            'nama_unit' => 'Boneka Voodoo Premium',
            'kode_unit' => 'SNT-001',
            'status' => 'Tersedia'
        ],
        (object)[
            'nama_unit' => 'Minyak Pelet Jaran Goyang',
            'kode_unit' => 'SNT-002',
            'status' => 'Dipinjam'
        ],
        (object)[
            'nama_unit' => 'Keris Terbang Banten',
            'kode_unit' => 'SNT-003',
            'status' => 'Tersedia'
        ],
        (object)[
            'nama_unit' => 'Kemenyan Arab Super',
            'kode_unit' => 'SNT-004',
            'status' => 'Tersedia'
        ],
    ];

    return view('katalog', compact('units'));
});

Route::get('/admin', function () {
    // Data dummy sementara
    $units = [
        (object)['kode_unit' => 'SNT-001', 'nama_unit' => 'Boneka Voodoo Premium', 'emoji' => '🪆', 'kategori' => ['Santet', 'Premium'], 'status' => 'Tersedia'],
        (object)['kode_unit' => 'SNT-002', 'nama_unit' => 'Minyak Pelet Jaran Goyang', 'emoji' => '🧴', 'kategori' => ['Pelet'], 'status' => 'Dipinjam'],
        (object)['kode_unit' => 'SNT-003', 'nama_unit' => 'Keris Terbang Banten', 'emoji' => '🗡️', 'kategori' => ['Pusaka', 'Santet'], 'status' => 'Tersedia'],
        (object)['kode_unit' => 'SNT-004', 'nama_unit' => 'Kemenyan Arab Super', 'emoji' => '🪔', 'kategori' => ['Ritual'], 'status' => 'Tersedia'],
    ];

    // Hitung statistik (simulasi)
    $totalUnit = count($units);
    $sedangDipinjam = collect($units)->where('status', 'Dipinjam')->count();

    return view('admin', compact('units', 'totalUnit', 'sedangDipinjam'));
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/admin/create', function () {
    return view('admin_unit_create');
});

Route::get('/profil', function () {
    $riwayat = [
        (object)[
            'nama_unit' => 'Santet Sepi Malam', 
            'tgl_pinjam' => '12 Okt 2026', 
            'jatuh_tempo' => '19 Okt 2026', 
            'status' => 'Sedang Dipinjam'
        ],
        (object)[
            'nama_unit' => 'Keris Pusaka Kyai Semar', 
            'tgl_pinjam' => '15 Sep 2026', 
            'jatuh_tempo' => '22 Sep 2026', 
            'status' => 'Terlambat'
        ],
        (object)[
            'nama_unit' => 'Pelet Pengikat Hati', 
            'tgl_pinjam' => '28 Sep 2026', 
            'jatuh_tempo' => '05 Okt 2026', 
            'status' => 'Dikembalikan'
        ],
    ];

    return view('profil', compact('riwayat'));
});

Route::get('/admin/pengembalian', function () {
    $peminjaman = [
        (object)[
            'nama_peminjam' => 'Mbah Maridjan', 'emoji_user' => '🧑', 'nama_unit' => 'Keris Pusaka Kyai Semar',
            'tgl_pinjam' => '14 Okt 2026', 'jatuh_tempo' => '21 Okt 2026', 'hari_terlambat' => 3, 'denda' => 15000
        ],
        (object)[
            'nama_peminjam' => 'Nenek Gayung', 'emoji_user' => '👩', 'nama_unit' => 'Pelet Pengikat Hati',
            'tgl_pinjam' => '18 Okt 2026', 'jatuh_tempo' => '25 Okt 2026', 'hari_terlambat' => 0, 'denda' => 0
        ],
        (object)[
            'nama_peminjam' => 'Pak Bondan', 'emoji_user' => '👨', 'nama_unit' => 'Boneka Voodoo Klasik',
            'tgl_pinjam' => '20 Okt 2026', 'jatuh_tempo' => '27 Okt 2026', 'hari_terlambat' => 0, 'denda' => 0
        ],
    ];

    $statDipinjam = count($peminjaman);
    $statTerlambat = collect($peminjaman)->where('hari_terlambat', '>', 0)->count();
    $statDenda = collect($peminjaman)->sum('denda');

    return view('admin_pengembalian', compact('peminjaman', 'statDipinjam', 'statTerlambat', 'statDenda'));
});
