# Dokumentasi Teknis Santika

## 1. Platform

| Komponen | Implementasi |
|---|---|
| Backend | PHP `^8.2`, Laravel `^12.0` |
| View | Blade |
| Styling | Tailwind CSS v4 browser engine via CDN pada layout aplikasi; dependensi Tailwind/Vite juga tersedia di package frontend |
| Database | Didefinisikan melalui Laravel migrations; MySQL untuk penggunaan lokal sebagaimana panduan README, SQLite dipakai oleh test suite |
| Test | PHPUnit melalui `php artisan test` |
| Config bisnis | `config/santika.php` |

## 2. Arsitektur aplikasi

```text
Browser
  -> routes/web.php
     -> middleware guest/auth/admin/member
        -> Controller
           -> Form Request (validasi dan otorisasi request)
           -> Model / Eloquent
           -> LoanService (aturan pinjam dan kembali)
              -> Database transaction dan row lock
        -> Blade views
```

### Batas route utama

| Area | Route utama | Proteksi |
|---|---|---|
| Login | `GET/POST /login` | `guest`; POST memakai throttle |
| Logout | `POST /logout` | `auth` |
| Katalog dan peminjaman anggota | `/katalog`, `/pinjam/{unit}`, `/pinjaman-saya*`, `/profile*` | `auth` + `member` |
| Dashboard Admin | `/admin` | `auth` + `admin` |
| Kelola unit/kategori/anggota | `/admin/units*`, `/admin/categories*`, `/admin/members*` | `auth` + `admin` |
| Pengembalian dan riwayat | `/admin/returns*` | `auth` + `admin` |

Alias middleware ditetapkan di `bootstrap/app.php`. Form Request untuk data Admin juga memeriksa role Admin sebagai lapisan otorisasi tambahan. Layout saat ini memuat Tailwind browser engine dan Google Fonts dari CDN, sehingga tampilan/font dapat memerlukan akses internet.

## 3. Lokasi kode penting

| Area | Path |
|---|---|
| Route web | `routes/web.php` |
| Middleware role | `app/Http/Middleware/EnsureUserIsAdmin.php`, `EnsureUserIsMember.php` |
| Form Request | `app/Http/Requests/` |
| Controllers | `app/Http/Controllers/` |
| Aturan pinjam/kembali | `app/Services/LoanService.php` |
| Model | `app/Models/` |
| Struktur database | `database/migrations/` |
| Data awal | `database/seeders/DatabaseSeeder.php` |
| View bersama | `resources/views/layouts/`, `resources/views/partials/` |
| Test | `tests/Feature/`, `tests/Unit/` |
| BRD/PRD/diagram/UAT | `docs/` |

## 4. Konfigurasi bisnis

Konfigurasi dibaca dari `config/santika.php`; jangan memanggil `env()` langsung dari Controller atau View.

| Key | Default | Fungsi |
|---|---:|---|
| `santika.denda_per_hari` | `50000` | Denda tetap per hari keterlambatan, Rupiah |
| `santika.batas_pinjam` | `2` | Batas pinjaman aktif anggota |
| `santika.durasi_pinjam` | `5` | Durasi pinjam dalam hari |
| `santika.demo_password` | `password` | Password akun demo lokal |

Environment override: `DENDA_PER_HARI`, `BATAS_PINJAM`, `DURASI_PINJAM`, dan `SANTIKA_DEMO_PASSWORD`. Perubahan environment saat config cache aktif memerlukan `php artisan config:clear` atau regenerasi config cache.

## 5. Aturan transaksi peminjaman

1. Pastikan profil anggota tersedia.
2. Buka database transaction, lock row profil untuk menyerialisasi peminjaman anggota yang sama.
3. Lock row unit dan pastikan status masih tersedia.
4. Hitung pinjaman aktif dan tolak bila telah mencapai `santika.batas_pinjam`.
5. Buat transaksi dengan jatuh tempo tanggal pinjam + `santika.durasi_pinjam`.
6. Ubah status unit menjadi `dipinjam`.

Lock dan transaction mencegah dua request bersamaan melewati batas pinjaman atau mengambil unit fisik yang sama.

## 6. Aturan transaksi pengembalian

1. Hanya route Admin dapat memanggil proses pengembalian.
2. Lock row transaksi dan pastikan status masih `dipinjam`.
3. Hitung hari terlambat dari tanggal kembali terhadap jatuh tempo; nilai minimum nol.
4. Hitung `denda = hari_terlambat × santika.denda_per_hari`.
5. Simpan tanggal kembali, hari terlambat, denda final, Admin pemroses, dan status `dikembalikan`.
6. Ubah status unit menjadi `tersedia`.

Denda tersimpan pada transaksi sehingga riwayat yang sudah selesai tidak dihitung ulang saat konfigurasi berubah.

## 7. Database dan integritas

Skema ERD dan kardinalitas tersedia di [`flowchart-erd.md`](flowchart-erd.md). Migrations menetapkan foreign key dan unique constraint untuk kode unit, email, kategori, profil, serta nomor anggota. Penghapusan data yang direferensikan transaksi dibatasi agar riwayat tidak hilang.

## 8. Menjalankan dan menguji

Langkah instalasi lengkap ada di [`README.md`](../README.md). Perintah pengembangan/test utama:

```bash
composer install
npm install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Untuk menjalankan test:

```bash
php artisan test
```

Suite terakhir yang dicatat pada `docs/UAT.md` lulus **21 test dan 373 assertions**. Test memakai database test yang dikonfigurasi PHPUnit; jangan menjalankan `migrate:fresh` pada database berisi data penting.

## 9. Dokumentasi terkait

- [BRD](BRD_Santika.md)
- [PRD](PRD_Santika.md)
- [Flowchart dan ERD](flowchart-erd.md)
- [Design specification dan F&B clarification](design-spec.md)
- [Requirement traceability](requirement-traceability.md)
- [UAT](UAT.md)
- [Panduan pengguna](manual-book.md)
- [Register deliverable](deliverable-status.md)
