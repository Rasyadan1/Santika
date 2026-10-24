# Panduan Pengguna Santika

Santika adalah aplikasi simulasi penyewaan unit mistis. Seluruh unit dan layanan di dalamnya bersifat fiktif.

## 1. Masuk ke aplikasi

1. Buka alamat aplikasi, misalnya `http://127.0.0.1:8000`.
2. Masukkan email dan password akun yang dibuat Admin atau akun demo dari seeder.
3. Tekan **Masuk ke Alam Gaib**. Ikon mata di kolom password dapat dipakai untuk memeriksa ketikan sebelum masuk.
4. Gunakan **Keluar** untuk mengakhiri sesi.

Registrasi publik tidak tersedia. Admin membuat akun anggota melalui **Kelola Data → Anggota**.

### Akun demo seeder lokal

| Role | Email | Password default |
|---|---|---|
| Admin | `admin@santika.test` | `password` |
| Anggota | `anggota@santika.test` | `password` |
| Anggota | `anggota2@santika.test` | `password` |

Password bisa diubah dengan konfigurasi `SANTIKA_DEMO_PASSWORD` sebelum menjalankan seeder. Akun demo dan password sederhana hanya untuk lingkungan lokal.

## 2. Panduan Admin

### Mengelola kategori

1. Buka **Kelola Data → Kategori**.
2. Pilih **Tambah Kategori**, isi nama kategori dan deskripsi, lalu simpan.
3. Gunakan aksi **Edit** untuk memperbarui kategori.
4. Kategori yang masih dipakai unit tidak dapat dihapus.

### Mengelola unit

1. Buat kategori terlebih dahulu.
2. Buka **Kelola Data → Unit** lalu pilih **Tambah Unit**.
3. Isi kode unik, nama, tarif per hari, deskripsi, dan pilih minimal satu kategori.
4. Simpan. Nama unit boleh sama, tetapi kode unit harus berbeda.
5. Gunakan aksi **Edit** atau **Hapus** dari daftar unit. Unit yang sedang dipinjam atau memiliki riwayat pinjaman tidak dapat dihapus.

### Mengelola anggota

1. Buka **Kelola Data → Anggota** lalu pilih **Tambah Anggota**.
2. Isi nama, email, password, konfirmasi password, dan informasi kontak bila tersedia.
3. Sistem membuat akun ber-role anggota dan profil anggotanya.
4. Pada edit anggota, kosongkan kedua kolom password bila password tidak ingin diganti.
5. Anggota dengan pinjaman aktif atau riwayat transaksi yang terhubung tidak dapat dihapus.

### Memproses pengembalian

1. Buka menu **Pengembalian** (halaman berjudul **Pengembalian Aktif**).
2. Tinjau tanggal pinjam, jatuh tempo, hari keterlambatan, dan estimasi denda.
3. Tekan **Proses Pengembalian** setelah unit benar-benar diterima.
4. Sistem menyimpan tanggal kembali, admin pemroses, hari terlambat, dan denda final serta mengubah status unit menjadi tersedia.

Anggota tidak dapat memproses pengembalian dari aplikasi; mereka perlu menghubungi Admin.

### Melihat dan mencetak laporan

1. Buka **Riwayat Peminjaman**.
2. Filter transaksi berdasarkan anggota dan/atau status bila diperlukan.
3. Pilih **Cetak Anggota** untuk membuka laporan seorang anggota.
4. Tekan **Cetak Laporan** dan pilih printer atau **Save as PDF** pada dialog browser.

## 3. Panduan Anggota

### Mengubah profil

Buka menu **Profil** lalu **Ubah Profil**. Nama, nomor telepon, dan alamat dapat diperbarui. Email tidak dapat diubah dari halaman profil. Kolom password bersifat opsional; kosongkan jika tidak ingin mengganti password.

### Mencari dan meminjam unit

1. Buka **Katalog**.
2. Masukkan nama unit pada kolom pencarian dan tekan **Cari**.
3. Unit dengan status **Tersedia** dapat dipinjam melalui tombol **Pinjam Unit**.
4. Sistem menolak unit yang sedang dipinjam dan menolak peminjaman baru jika sudah ada dua pinjaman aktif.
5. Batas masa pinjam adalah lima hari. Jatuh tempo ditampilkan pada daftar pinjaman aktif.

### Melihat pinjaman

Buka **Pinjaman** untuk melihat transaksi aktif dan status keterlambatan. Pilih **Riwayat** untuk melihat transaksi sebelumnya, termasuk tanggal kembali dan denda yang sudah dicatat.

## 4. Aturan denda

Denda keterlambatan dihitung saat Admin memproses pengembalian:

```text
hari terlambat = max(0, tanggal kembali - tanggal jatuh tempo)
denda = hari terlambat x DENDA_PER_HARI
```

Tarif denda default adalah **Rp50.000 per hari keterlambatan**, terpisah dari tarif sewa unit. Nilai konfigurasi dapat diubah melalui `DENDA_PER_HARI`. Contoh: keterlambatan 2 hari menghasilkan denda Rp100.000.
