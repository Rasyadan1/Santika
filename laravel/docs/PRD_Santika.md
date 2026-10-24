# Product Requirements Document (PRD) — Santika

| Atribut | Nilai |
|---|---|
| Produk | Santika |
| Versi | 1.0 |
| Status | Draft untuk tinjauan dan UAT |
| Pemilik produk | Stakeholder proyek Santika |
| Dokumen terkait | [BRD](BRD_Santika.md), [matriks keterlacakan SRS](requirement-traceability.md), [UAT](UAT.md), [manual pengguna](manual-book.md) |

## 1. Ringkasan produk

Santika adalah aplikasi web berbasis Laravel untuk menyewakan unit fisik kepada anggota terdaftar. Produk menyediakan dua pengalaman berdasarkan role: Admin mengelola data dan proses pengembalian; anggota menggunakan katalog dan mengelola transaksi sendiri.

## 2. Sasaran produk

- Menyediakan alur penyewaan sederhana dan dapat ditelusuri.
- Menjaga batas jumlah pinjaman, tanggal jatuh tempo, serta status unit secara konsisten.
- Melindungi data anggota dan membatasi aksi sensitif ke role yang tepat.
- Menyediakan daftar dan laporan yang berguna untuk administrasi.

## 3. Persona dan kebutuhan

### Admin

- Ingin mengelola unit, kategori, dan akun anggota.
- Ingin melihat pinjaman yang belum kembali, memproses unit yang diterima, serta mengetahui denda.
- Ingin meninjau dan mencetak riwayat seorang anggota.

### Anggota

- Ingin menemukan unit berdasarkan nama dan melihat ketersediaannya.
- Ingin meminjam unit yang tersedia selama batas pinjaman belum tercapai.
- Ingin melihat tanggal jatuh tempo, pinjaman aktif, riwayat, dan mengubah profilnya sendiri.

## 4. Peta modul dan acceptance criteria

| Modul | Kebutuhan produk | Kriteria penerimaan |
|---|---|---|
| Autentikasi | Login/logout berbasis sesi; tidak ada pendaftaran publik | Guest diarahkan ke login; hanya akun valid dapat login; logout mengakhiri sesi; login dibatasi throttle |
| Akses role | Halaman admin hanya untuk Admin; area anggota hanya untuk anggota berprofil | Akses GET maupun aksi POST/PUT/DELETE role yang salah ditolak tanpa mengubah data |
| Katalog | Daftar unit, kategori, status dan pencarian nama | Pencarian unit menampilkan hasil yang cocok; unit dipinjam tidak dapat dipinjam ulang |
| Profil anggota | Tampilkan/edit akun dan profil anggota sendiri | Perubahan hanya berlaku pada anggota yang sedang login; password opsional dan perlu konfirmasi |
| Manajemen unit | Admin membuat, melihat, mengubah, menghapus unit | Kode wajib dan unik; nama boleh sama; kategori yang dipilih valid; unit bertransaksi tidak dihapus sembarangan |
| Manajemen kategori | Admin mengelola kategori | Nama wajib dan unik; kategori yang terhubung ke unit tidak dapat dihapus |
| Manajemen anggota | Admin membuat/mengubah/menghapus akun anggota | Email unik; password wajib saat buat dan opsional saat ubah; role hasil pembuatan selalu anggota; profil anggota terkait dibuat |
| Peminjaman | Anggota meminjam unit tersedia | Maksimal dua pinjaman aktif per anggota; transaksi baru memiliki tanggal pinjam hari ini dan jatuh tempo lima hari kemudian; unit berubah menjadi dipinjam |
| Pengembalian | Admin memproses pengembalian | Hanya pinjaman aktif yang dapat diproses; data tanggal/status/denda/Admin disimpan; unit menjadi tersedia |
| Denda | Denda tetap per hari terlambat | Default Rp50.000/hari, terpisah dari tarif sewa; 0 hari terlambat = Rp0; 2 hari terlambat = Rp100.000 |
| Riwayat dan laporan | Anggota melihat miliknya; Admin melihat/filter dan mencetak laporan | Anggota tidak melihat data anggota lain; Admin dapat membuka laporan per anggota dan mencetaknya |
| Pencarian admin (tambahan) | Admin mencari kategori/anggota lintas halaman | Query pencarian diterapkan ke seluruh data dan tetap terbawa pada pagination |

## 5. Alur utama pengguna

### 5.1 Anggota meminjam unit

1. Anggota login.
2. Anggota mencari dan memilih unit berstatus tersedia.
3. Sistem memeriksa role/profil, status unit, dan jumlah pinjaman aktif.
4. Jika lolos, sistem membuat transaksi dan mengubah status unit menjadi dipinjam.
5. Anggota melihat jatuh tempo pada daftar pinjaman aktif.

### 5.2 Admin memproses pengembalian

1. Admin membuka daftar pengembalian aktif.
2. Admin memeriksa anggota, unit, jatuh tempo, keterlambatan, dan estimasi denda.
3. Admin menerima unit dan menekan proses pengembalian.
4. Sistem menghitung hari terlambat dan denda dari konfigurasi tarif harian.
5. Sistem menyimpan tanggal kembali, Admin pemroses, denda final, status transaksi, dan ketersediaan unit.

Diagram alur dan ERD rinci tersedia di [`flowchart-erd.md`](flowchart-erd.md).

## 6. Aturan bisnis produk

- Role hanya `admin` dan `user` (anggota).
- Profil wajib untuk anggota; tidak ada profil anggota untuk Admin.
- Unit memakai `kode_unit` unik; nama boleh sama.
- Unit dikaitkan ke satu atau lebih kategori melalui relasi many-to-many.
- Maksimal dua pinjaman aktif per anggota.
- Durasi pinjam lima hari kalender.
- Denda default Rp50.000 per hari kalender terlambat dan dapat dioverride melalui konfigurasi environment `DENDA_PER_HARI`.
- Hanya Admin dapat memproses pengembalian.
- Pinjaman dan riwayat anggota discope ke profil anggota yang sedang login.

## 7. Data produk

| Entitas | Informasi kunci |
|---|---|
| User | Nama, email unik, password hash, role |
| Profile | `user_id` unik, nomor anggota unik, telepon, alamat, tanggal daftar; untuk anggota |
| Category | Nama unik, deskripsi |
| Unit | Kode unik, nama, deskripsi, status, tarif sewa per hari |
| CategoryUnit | Pivot many-to-many unit/kategori |
| Peminjaman | Anggota, unit, tanggal pinjam/jatuh tempo/kembali, status, hari terlambat, denda, Admin pemroses |

## 8. Keamanan, validasi, dan kualitas

- Server-side validation menggunakan Form Request dan aturan bisnis.
- Middleware `auth`, `admin`, dan `member` membatasi route.
- Password disimpan dengan hash melalui cast model.
- Transaksi database dan row lock digunakan untuk menjaga batas pinjaman/ketersediaan unit pada operasi bersamaan.
- Foreign key dan unique constraint menjaga integritas data.
- Test otomatis dijalankan dengan `php artisan test`; hasil terakhir dicatat di UAT.
- UAT browser oleh stakeholder tetap wajib sebelum penerimaan final.

## 9. Di luar lingkup saat ini

Registrasi publik, profil Admin, pengingat otomatis, pelacakan pembayaran denda, pembayaran online, serta integrasi eksternal tidak termasuk dalam rilis requirement ini.

## 10. Risiko dan keputusan terbuka

- Persetujuan formal atas perubahan FR-04 menjadi profil khusus anggota.
- Konfirmasi bahwa Admin tidak menyewa unit.
- Konfirmasi tarif denda Rp50.000/hari dan cara hitung hari kalender.
- Klarifikasi “F&B” dalam workflow sumber.
- UAT stakeholder dan persetujuan cetak laporan pada browser/perangkat target.
