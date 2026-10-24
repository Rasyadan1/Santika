# Matriks Keterlacakan Requirement Santika

Dokumen ini memetakan requirement pada SRS ke implementasi dan bukti pengujian yang tersedia. Status implementasi **bukan** pengganti persetujuan UAT oleh stakeholder.

## Legenda status

- **Terverifikasi otomatis**: ada implementasi dan pengujian otomatis yang relevan.
- **Diimplementasikan; UAT manual menunggu**: implementasi ditemukan, tetapi perlu dijalankan dan disetujui pengguna/stakeholder.
- **Perlu klarifikasi/perubahan SRS**: implementasi mengikuti keputusan proyek, tetapi redaksi requirement perlu diselaraskan.
- **Perlu konfirmasi deliverable**: artefak belum ditemukan di `laravel/docs/` saat audit.

## Functional requirements

| ID | Ringkasan requirement | Implementasi / lokasi | Bukti otomatis | Status / tindak lanjut |
|---|---|---|---|---|
| FR-01 | Role Admin dan User/Anggota | `routes/web.php`, `EnsureUserIsAdmin`, `EnsureUserIsMember`, `users.role` | `AccessControlTest` role admin/anggota | Terverifikasi otomatis |
| FR-02 | Halaman aplikasi memerlukan login | Route aplikasi berada dalam middleware `auth`; route login untuk guest | `test_guest_can_only_reach_the_login_page`, logout test | Terverifikasi otomatis; konfirmasi alur browser saat UAT |
| FR-03 | Hanya anggota terdaftar dapat meminjam | Middleware `member`, `LoanController`, `LoanService` | `test_user_without_member_profile_cannot_access_member_pages`, role-action test | Terverifikasi otomatis |
| FR-04 | Setiap user memiliki tepat satu profil | `profiles.user_id` unique; profil dibuat untuk anggota di `MemberController` dan seeder | Seeder test memeriksa profil anggota | **Perlu klarifikasi/perubahan SRS:** keputusan proyek adalah tidak membuat profil Admin. Ubah redaksi menjadi “setiap anggota memiliki tepat satu profil”. |
| FR-05 | Anggota dapat mengubah profil sendiri | `ProfileController`, `ProfileRequest`; route profil tidak menerima ID user lain | `test_member_can_update_only_their_profile_and_view_their_loan_pages` | Terverifikasi otomatis; verifikasi alur edit saat UAT |
| FR-06 | Unit dapat memiliki banyak kategori | Pivot `category_unit`, relasi model, `UnitController::store/update` memakai `sync` | Seeder membuat unit yang terhubung ke lebih dari satu kategori | Terverifikasi otomatis melalui data seeder; konfirmasi UI saat UAT |
| FR-07 | Nama unit boleh sama; kode membedakan unit fisik | `units.nama_unit` tidak unique; `units.kode_unit` unique | `test_demo_seeder_can_be_run_repeatedly_without_duplicates` memeriksa dua unit bernama sama dengan kode berbeda | Terverifikasi otomatis |
| FR-08 | Kode unit wajib dan unik | `UnitRequest`, unique constraint migration `units.kode_unit` | `test_admin_forms_validate_required_unique_and_related_values` | Terverifikasi otomatis |
| FR-09 | Anggota mencari unit berdasarkan nama | `UnitController::katalog`, form pencarian katalog | `test_member_can_search_catalog_by_unit_name` | Terverifikasi otomatis |
| FR-10 | Admin CRUD unit | Resource routes dan `UnitController` | `test_admin_can_create_categories_units_and_member_accounts`; validasi unit | Diimplementasikan; UAT manual untuk update/delete menunggu |
| FR-11 | Admin CRUD kategori | Resource routes dan `CategoryController` | Test validasi kategori dan halaman admin | Diimplementasikan; UAT manual untuk update/delete dan larangan hapus kategori terpakai menunggu |
| FR-12 | Admin CRUD anggota | Resource routes dan `MemberController`; role saat pembuatan ditetapkan `user` | Test pembuatan anggota, role spoofing, dan validasi | Diimplementasikan; UAT manual untuk update/delete menunggu |
| FR-13 | Maksimal dua pinjaman aktif per anggota | `LoanService::pinjam`, konfigurasi `santika.batas_pinjam` | `test_member_cannot_exceed_the_active_loan_limit` | Terverifikasi otomatis |
| FR-14 | Masa pinjam maksimal lima hari | `LoanService::pinjam`, konfigurasi `santika.durasi_pinjam` | `test_member_can_borrow_a_unit_and_admin_can_process_late_return` memeriksa jatuh tempo | Terverifikasi otomatis |
| FR-15 | Denda untuk pengembalian terlambat | `LoanService::kembalikan`, `config/santika.php` | Test pengembalian terlambat memeriksa estimasi dan denda final | Aturan proyek ditetapkan **Rp50.000 per hari**, terpisah dari tarif sewa; UAT manual menunggu |
| FR-16 | Hanya Admin memproses pengembalian | Route `admin.returns.process` di dalam middleware `admin` | `test_role_middleware_blocks_admin_mutations_and_member_actions` | Terverifikasi otomatis |
| FR-17 | Anggota menghubungi Admin; tidak mengembalikan sendiri melalui sistem | Tidak ada route pengembalian anggota; aksi hanya tersedia di area Admin | Role-action test menolak anggota pada route proses pengembalian | Terverifikasi otomatis; komunikasi di luar aplikasi perlu prosedur operasional |
| FR-18 | Admin melihat semua unit/pinjaman yang sedang dipinjam | `ReturnController::index`, query pinjaman aktif | Halaman pengembalian dirender oleh `test_admin_can_open_admin_dashboard_but_cannot_use_member_catalog` | Diimplementasikan; cakupan semua data perlu konfirmasi UAT |
| FR-19 | Anggota hanya melihat pinjamannya sendiri | Query `LoanController::index/riwayat` dibatasi ke user login | `test_member_loan_pages_only_show_their_own_transactions` | Terverifikasi otomatis |
| FR-20 | Admin melihat dan mencetak riwayat anggota | `ReturnController::riwayat/cetakLaporan`, view laporan cetak | `test_admin_forms_history_and_member_report_render` | Diimplementasikan; hasil cetak browser/PDF perlu UAT manual |

## Validation requirements

| ID | Ringkasan requirement | Implementasi / bukti | Status / tindak lanjut |
|---|---|---|---|
| VR-01 | Validasi server-side dan field wajib | `UnitRequest`, `CategoryRequest`, `MemberRequest`, `ProfileRequest`, `LoginRequest`; test wajib, email dan password | Implementasi dan kasus utama terverifikasi otomatis; uji semua variasi pesan pada UAT |
| VR-02 | Kode unit wajib dan unik | Rule `required`/`unique` dan unique constraint database; test unit create/edit | Terverifikasi otomatis |
| VR-03 | Menolak pinjaman baru jika sudah ada dua pinjaman aktif | `LoanService::pinjam`; test batas pinjaman | Terverifikasi otomatis |
| VR-04 | Penyewa harus anggota terdaftar | Route `member`, wajib role user dan profil; tidak ada pilihan renter bebas | Terverifikasi otomatis melalui penolakan role/non-profile |

## Technical requirements

| ID | Ringkasan requirement | Bukti implementasi | Status / tindak lanjut |
|---|---|---|---|
| TR-01 | PHP dan Laravel | `composer.json`, aplikasi Laravel | Terpenuhi |
| TR-02 | Struktur database melalui migrations | `database/migrations/` | Terpenuhi; migration dijalankan oleh test suite |
| TR-03 | Data awal melalui seeders | `database/seeders/DatabaseSeeder.php` | Terverifikasi otomatis; seeder dapat dijalankan berulang tanpa duplikasi |
| TR-04 | Validasi sisi server, misalnya Form Request | Form Request di `app/Http/Requests/` | Terverifikasi otomatis untuk skenario utama |
| TR-05 | Pembatasan fitur berdasarkan role | Middleware `admin`/`member`, alias pada `bootstrap/app.php`, route grouping | Terverifikasi otomatis untuk akses baca dan aksi tulis yang diuji |

## Bukti test otomatis

Test suite saat audit mencakup kontrol akses, navigasi, validasi form, pencarian, batas pinjaman, perhitungan denda, isolasi riwayat anggota, dan laporan. Jalankan ulang dengan:

```bash
php artisan test
```

Hasil suite otomatis terakhir dicatat di `docs/UAT.md`. Test otomatis membuktikan perilaku kode pada environment test; test ini **tidak** dianggap sebagai persetujuan UAT stakeholder.

## Keputusan ruang lingkup yang harus diselaraskan

1. **Profil:** profil diwajibkan untuk anggota, bukan akun Admin. SRS FR-04 saat ini menyebut “setiap user”; minta persetujuan stakeholder untuk mengubah redaksi tersebut.
2. **Denda:** denda keterlambatan adalah Rp50.000 per hari. Implementasi menggunakan `DENDA_PER_HARI` dengan default 50000; environment dapat mengubah nilainya.
3. **Pendaftaran:** registrasi publik tidak tersedia; anggota dibuat Admin, sesuai asumsi pada SRS.

## Deliverable workflow

| Deliverable | Status berdasarkan repository saat audit |
|---|---|
| UAT | Skenario tersedia di `docs/UAT.md`; pelaksanaan dan tanda tangan stakeholder masih menunggu |
| User manual | Tersedia di `docs/manual-book.md`; perlu dicocokkan dengan hasil UAT final |
| Project documentation | `README.md` tersedia; BRD, PRD, dan file flowchart/ERD belum ditemukan di `laravel/docs/` saat audit—pastikan apakah disimpan di lokasi lain |
| Push ke GitLab | Tidak dapat diverifikasi dari workspace; pastikan commit dan dokumen akhir sudah dipush ke repository tim |
| “Design, F&B” | Makna F&B belum didefinisikan di SRS; perlu dikonfirmasi kepada stakeholder |
