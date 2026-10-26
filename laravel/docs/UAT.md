# Rencana dan Catatan UAT Santika

Dokumen ini memuat skenario untuk dilaksanakan Admin/anggota uji atau stakeholder pada browser. Status **Belum dijalankan** berarti skenario belum diuji dan disetujui oleh pengguna; hasil test otomatis tidak menggantikannya.

Matriks ID requirement dan bukti implementasi ada di [`requirement-traceability.md`](requirement-traceability.md).

## Informasi pelaksanaan

| Item | Isian |
|---|---|
| Versi/build aplikasi | Versi 1.0 |
| Environment dan database | MySQL |
| Tanggal pengujian | 26 Oktober 2026 |
| Penguji/stakeholder | Arjuna Rayihan Jayadi |
| Browser/perangkat | Brave |
| Admin uji | `admin@santika.test` (lokal) |
| Anggota uji | `anggota@santika.test` (lokal) |
| Password demo default | `password` (lokal saja) |
| Tarif denda yang disepakati | Rp50.000 per hari keterlambatan |

Lakukan UAT pada database uji, bukan database produksi. Siapkan satu Admin, minimal dua anggota, kategori, beberapa unit tersedia, dan data pinjaman yang bisa dikembalikan. Untuk menguji kasus terlambat, buat transaksi dengan jatuh tempo dua hari sebelum tanggal pengujian atau gunakan data uji yang setara.

## Skenario UAT

| ID | Requirement | Area | Langkah uji | Hasil yang diharapkan | Status | Catatan aktual |
|---|---|---|---|---|---|---|
| UAT-01 | FR-02 | Akses guest | Keluar, lalu buka `/`, `/katalog`, dan `/admin` | Guest tidak dapat melihat halaman aplikasi dan diarahkan ke login | Sudah dijalankan | Sukses |
| UAT-02 | FR-01, FR-02 | Login Admin | Login dengan akun Admin yang valid | Admin masuk ke dashboard dan dapat membuka navigasi administrasi | Sudah dijalankan | Sukses |
| UAT-03 | FR-01–03 | Login anggota | Login dengan akun anggota yang memiliki profil | Anggota masuk ke katalog dan melihat navigasi anggota | Sudah dijalankan | Sukses |
| UAT-04 | FR-01, TR-05 | Batas role | Sebagai anggota, buka `/admin` dan coba kirim aksi tambah data/pengembalian ke endpoint Admin | Akses ditolak; tidak ada data yang berubah | Sudah dijalankan | Sukses |
| UAT-05 | FR-11, VR-01 | Kategori | Admin tambah, edit, dan hapus kategori yang tidak digunakan; coba hapus kategori yang masih dipakai unit | CRUD berhasil untuk data valid; kategori terpakai tidak dapat dihapus | Sudah dijalankan | Sukses |
| UAT-06 | FR-06–08, FR-10 | Unit | Admin tambah unit dengan kategori; coba dua unit bernama sama dengan kode berbeda | Kedua unit tersimpan; kategori dapat dikaitkan; kode unit tetap unik | Sudah dijalankan | Sukses |
| UAT-07 | FR-08, VR-01–02 | Validasi unit | Kirim form tanpa kode/nama/tarif/kategori; coba kode duplikat dan kategori yang tidak valid | Server menolak input dan menampilkan validasi; tidak ada unit invalid tersimpan | Sudah dijalankan | Sukses |
| UAT-08 | FR-12, VR-01, VR-04 | Anggota | Admin buat dan edit anggota; coba email duplikat, password tidak cocok, serta kirim field role bernilai `admin` | Input invalid ditolak; akun hasil pembuatan tetap role anggota dan memiliki profil | Sudah dijalankan | Sukses |
| UAT-09 | FR-04–05 | Profil anggota | Anggota ubah nama, telepon, alamat, dan password; login kembali dengan password baru | Hanya data akun anggota tersebut berubah; Admin tidak memerlukan profil anggota | Sudah dijalankan | Sukses |
| UAT-10 | FR-09 | Pencarian katalog | Anggota cari unit berdasarkan nama | Hasil hanya menampilkan unit yang cocok; unit lain tidak tampil | Sudah dijalankan | Sukses |
| UAT-11 | FR-03, FR-14 | Peminjaman | Anggota pinjam unit tersedia | Transaksi tercatat, unit berubah menjadi dipinjam, jatuh tempo lima hari setelah tanggal pinjam | Belum dijalankan | |
| UAT-12 | FR-13, VR-03 | Batas pinjaman | Anggota dengan dua pinjaman aktif mencoba pinjam unit ketiga | Permintaan ditolak; jumlah pinjaman dan status unit tidak berubah | Sudah dijalankan | Sukses |
| UAT-13 | FR-16–17 | Pengembalian tepat waktu | Anggota menghubungi Admin; Admin proses pengembalian sebelum/tepat jatuh tempo | Hanya Admin dapat memproses; denda nol dan unit kembali tersedia | Sudah dijalankan | Sukses |
| UAT-14 | FR-15 | Denda terlambat | Proses pengembalian yang terlambat dua hari | Hari terlambat = 2; denda final = **Rp100.000** (2 × Rp50.000); estimasi Admin memakai tarif yang sama | Sudah dijalankan | Sukses |
| UAT-15 | FR-19 | Privasi daftar anggota | Login sebagai anggota A dan buka pinjaman aktif serta riwayat | Hanya transaksi anggota A yang tampil, bukan transaksi anggota lain | Sudah dijalankan | Sukses |
| UAT-16 | FR-18 | Daftar Admin | Admin buka pengembalian aktif dengan beberapa transaksi aktif | Admin dapat melihat semua pinjaman aktif dan data anggota/unit yang sesuai | Sudah dijalankan | Sukses |
| UAT-17 | FR-20 | Filter riwayat | Admin filter riwayat berdasarkan anggota dan status, lalu buka laporan anggota | Hasil sesuai filter dan laporan anggota yang benar terbuka | Sudah dijalankan | Sukses |
| UAT-18 | FR-20 | Cetak laporan | Admin pilih cetak laporan dan gunakan dialog cetak browser/Save as PDF | Identitas anggota, rincian transaksi, total denda, dan layout cetak terbaca | Sudah dijalankan | Sukses |
| UAT-19 | FR-02 | Logout | Admin dan anggota logout, lalu buka kembali halaman yang sebelumnya terlindungi | Sesi berakhir dan halaman mengarahkan kembali ke login | Sudah dijalankan | Sukses |
| UAT-20 | Tambahan aplikasi | Pencarian daftar Admin | Cari kategori dan anggota yang diketahui berada di halaman data berbeda; pindah halaman hasil | Hasil dicari dari seluruh data, bukan hanya halaman yang sedang tampil; kata pencarian tetap terbawa saat pagination | Sudah dijalankan | Sukses |

## Hasil test otomatis (bukan persetujuan UAT)

Jalankan dari direktori `laravel/`:

```bash
php artisan test
```

Hasil terakhir (2026-10-25): **21 test dan 373 assertions lulus**. Ini bukti pengujian otomatis terhadap implementasi, bukan tanda tangan atau persetujuan UAT stakeholder.

## Catatan hasil UAT stakeholder

| Keputusan | Isian |
|---|---|
| Kesimpulan UAT | Belum dilaksanakan oleh stakeholder |
| Temuan/blocker | Fungsi utama dan fitur aplikasi berjalan dengan baik |
| Keputusan atas temuan | ______________________________ |
| Nama dan tanda tangan | Arjuna Rayihan Jayadi |
| Tanggal persetujuan | 26 Oktober 2026 |
