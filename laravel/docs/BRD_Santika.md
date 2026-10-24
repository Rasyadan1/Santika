# Business Requirements Document (BRD) — Santika

| Atribut | Nilai |
|---|---|
| Produk | Santika — Sewa Alat Santet dan Pelet Seketika |
| Versi dokumen | 1.0 |
| Status | Draft untuk tinjauan stakeholder |
| Framework target | Laravel |
| Anggota tim | Arjuna Rayihan Jayadi; Muhammad Rasyadan Agung Sedayu |
| Sumber kebutuhan | System Requirements Specification (SRS) Santika |

## 1. Ringkasan bisnis

Santika adalah aplikasi web untuk mengelola penyewaan unit secara terpusat. Anggota dapat mencari dan meminjam unit serta melihat pinjaman miliknya. Admin mengelola data unit, kategori, anggota, pengembalian, dan riwayat transaksi.

Aplikasi ini merupakan simulasi pembelajaran. Nama dan tema mistis pada unit bersifat fiktif.

## 2. Latar belakang dan kebutuhan

Proses pencatatan penyewaan perlu menghubungkan akun anggota, identitas unit fisik, status ketersediaan, masa pinjam, pengembalian, dan denda. Sistem perlu mencegah peminjaman oleh pengguna yang tidak terdaftar sebagai anggota, mencegah satu unit fisik dipinjam lebih dari satu anggota pada saat yang sama, serta menjaga riwayat transaksi.

## 3. Tujuan bisnis

1. Menyediakan katalog yang dapat dicari anggota.
2. Menjaga status unit dan transaksi tetap konsisten saat pinjam/kembali.
3. Menegakkan batas pinjaman dan durasi yang ditetapkan.
4. Membatasi administrasi data dan pemrosesan pengembalian kepada Admin.
5. Menyediakan riwayat dan laporan transaksi untuk keperluan administrasi.

## 4. Pemangku kepentingan dan pengguna

| Peran | Kebutuhan utama |
|---|---|
| Admin/Juru Kunci | Mengelola unit, kategori, akun anggota, memproses pengembalian, meninjau dan mencetak riwayat |
| Anggota | Mencari unit, meminjam unit yang tersedia, melihat transaksi sendiri, memperbarui profil sendiri |
| Pemilik/stakeholder proyek | Menetapkan kebijakan operasional dan menerima hasil UAT |
| Tim pengembang | Menjaga implementasi, data, dan dokumentasi sesuai requirement |

## 5. Lingkup

### Termasuk

- Login/logout dan pembatasan akses berdasarkan role.
- CRUD unit, kategori, dan anggota oleh Admin.
- Relasi many-to-many unit dan kategori.
- Pencarian katalog unit berdasarkan nama.
- Peminjaman anggota dengan batas maksimal dua pinjaman aktif dan masa pinjam lima hari.
- Pengembalian yang hanya dapat diproses Admin.
- Perhitungan denda keterlambatan Rp50.000 per hari.
- Daftar pinjaman aktif, riwayat anggota/admin, serta laporan cetak per anggota.
- Validasi server-side, migrations, seeders, automated tests, UAT, dan dokumentasi.

### Tidak termasuk dalam lingkup saat ini

- Registrasi publik.
- Profil anggota untuk akun Admin.
- Pengingat jatuh tempo otomatis.
- Pencatatan status pembayaran denda atau integrasi pembayaran.
- Integrasi layanan atau transaksi supernatural nyata.

## 6. Kebutuhan bisnis

| ID | Kebutuhan bisnis | Hasil yang diharapkan |
|---|---|---|
| BR-01 | Data dan aksi dibatasi menurut role | Anggota tidak mengakses fungsi Admin; Admin tidak menggunakan alur pinjam anggota |
| BR-02 | Unit fisik mempunyai identitas unik | Kode unik membedakan unit walaupun nama unit sama |
| BR-03 | Ketersediaan unit terjaga | Unit yang sedang dipinjam tidak dapat dipinjam kembali |
| BR-04 | Batas pinjaman diterapkan | Anggota tidak memiliki lebih dari dua pinjaman aktif |
| BR-05 | Masa pinjam dan denda jelas | Jatuh tempo lima hari setelah tanggal pinjam; denda Rp50.000 setiap hari terlambat |
| BR-06 | Pengembalian tercatat oleh Admin | Status, tanggal kembali, hari terlambat, denda, dan Admin pemroses tersimpan |
| BR-07 | Riwayat tersedia sesuai hak akses | Anggota melihat transaksi sendiri; Admin dapat melihat dan mencetak transaksi anggota |

## 7. Aturan dan asumsi bisnis

1. Akun memiliki role `admin` atau `user` (anggota).
2. Akun anggota dibuat oleh Admin; tidak ada registrasi publik.
3. Profil diwajibkan untuk anggota. Akun Admin tidak menggunakan profil anggota.
4. Setiap unit memiliki kode unik; nama unit boleh sama.
5. Setiap unit dalam alur pembuatan saat ini harus dikaitkan ke minimal satu kategori.
6. Satu anggota dapat memiliki paling banyak dua pinjaman berstatus aktif.
7. Tanggal jatuh tempo dihitung sebagai tanggal pinjam ditambah lima hari kalender.
8. Pengembalian pada tanggal jatuh tempo tidak dikenai denda; keterlambatan dihitung per hari kalender setelah tanggal jatuh tempo.
9. Denda final = hari terlambat × Rp50.000. Tarif disimpan dalam konfigurasi `DENDA_PER_HARI`; konfigurasi environment dapat mengubah default.
10. Admin mencatat pengembalian ketika unit benar-benar diterima. Anggota menghubungi Admin di luar aplikasi untuk menyerahkan unit.

## 8. Indikator penerimaan bisnis

- Guest tidak dapat mengakses halaman aplikasi yang terlindungi.
- Role dan profil anggota divalidasi sebelum akses alur anggota.
- Kode unit duplikat, input wajib yang kosong, dan kategori tidak valid ditolak.
- Peminjaman tidak melampaui batas jumlah dan tidak menurunkan ketersediaan unit secara tidak konsisten.
- Pengembalian tepat waktu menghasilkan denda nol; terlambat dua hari menghasilkan Rp100.000 pada konfigurasi default.
- Riwayat anggota tidak membocorkan transaksi anggota lain.
- UAT manual disetujui stakeholder dan temuan blocker ditutup atau diterima secara tertulis.

## 9. Keputusan dan hal yang memerlukan persetujuan

| Topik | Keputusan/isu |
|---|---|
| Profil FR-04 | SRS awal menyebut profil untuk “setiap user”. Keputusan proyek adalah hanya anggota memiliki profil. Redaksi SRS perlu diselaraskan dan disetujui. |
| Admin menyewa unit | Asumsi implementasi saat ini: Admin hanya mengelola sistem dan tidak menyewa unit. Konfirmasi stakeholder bila aturan berbeda. |
| Denda | Nilai ditetapkan Rp50.000 per hari; pastikan stakeholder menyetujui kebijakan dan perilaku tanggal kalender. |
| “F&B” pada workflow | Akronim belum dijelaskan sumber requirement; jangan menganggap arti tertentu sebelum stakeholder mengonfirmasi. |
| Hasil UAT | Skenario tersedia, tetapi pelaksanaan/tanda tangan stakeholder masih pending. |
