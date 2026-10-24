# Design Specification Santika

## 1. Status

Dokumen ini merangkum desain yang sedang diimplementasikan pada Blade views. Belum ada tautan file Figma atau paket desain terpisah di `laravel/docs/`; screenshot perlu ditambahkan setelah UAT browser.

## 2. Arah visual

- Tema gelap bernuansa mistis/komedi horor.
- Latar memakai warna gelap dan gradient mistis; warna primer ungu, warna destruktif merah/crimson.
- Font display `Creepster`; font isi `Space Grotesk`.
- Komponen memakai panel/card, border tipis, status chip, tabel, dan tombol dengan state hover/focus.
- Implementasi utama berada pada Blade views dan `resources/views/layouts/app.blade.php`.

## 3. Pola navigasi

### Anggota

- Menu konsisten: Katalog, Pinjaman, Riwayat, Profil.
- Halaman saat ini ditandai secara visual dan melalui `aria-current="page"`.

### Admin

- Menu konsisten: Dashboard, Kelola Data (dropdown Unit/Kategori/Anggota), Pengembalian, Riwayat Peminjaman.
- Dropdown Kelola Data mempertahankan status buka/tutup selama tab aktif.
- Logout terletak pada bagian bawah sidebar desktop; laporan cetak menyembunyikan navigasi saat print.

## 4. Pola halaman

| Tipe halaman | Pola tampilan |
|---|---|
| Daftar data | Judul dan aksi utama, pencarian/filter, tabel responsif, pagination |
| Form tambah/edit | Panel form, label dan error validasi, tombol simpan/kembali |
| Pinjaman anggota | Status pinjaman, tanggal jatuh tempo, unit, kategori |
| Pengembalian | Daftar pinjaman aktif, status keterlambatan, estimasi denda, aksi proses |
| Laporan cetak | Identitas anggota, ringkasan, tabel transaksi; aksi navigasi/cetak tidak ikut dicetak |

## 5. Interaksi dan aksesibilitas

- Form memiliki label, state fokus, dan tampilan pesan validasi.
- Menu dropdown berbasis elemen HTML native `details/summary`.
- Status aktif ditandai secara visual dan semantik.
- Tabel memakai pembungkus overflow horizontal untuk layar sempit.
- Tombol intip password menggunakan ikon mata pada form login.

## 6. Artefak visual untuk dilengkapi

| Artefak | Status |
|---|---|
| File design source/Figma | Belum tersedia di repository; perlu ditautkan jika ada |
| Screenshot login, katalog, pinjaman, admin, pengembalian, laporan | Belum disimpan; ambil setelah UAT pada environment yang disepakati |
| Konfirmasi responsive browser/perangkat | Masuk skenario UAT; belum disetujui stakeholder |

## 7. Klarifikasi “F&B”

Workflow sumber menulis “Design, F&B” tanpa definisi. Dokumen ini **tidak menafsirkan** akronim tersebut. Minta stakeholder menjelaskan istilah dan artefak yang dimaksud sebelum menandai deliverable terkait selesai.
