<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Peminjaman;
use App\Models\Profile;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seeder_can_be_run_repeatedly_without_duplicates(): void
    {
        $this->seed();
        $this->seed();

        $this->assertDatabaseCount('users', 3);
        $this->assertDatabaseCount('profiles', 2);
        $this->assertDatabaseCount('categories', 3);
        $this->assertDatabaseCount('units', 3);
        $this->assertDatabaseHas('users', [
            'email' => 'admin@santika.test',
            'role' => 'admin',
        ]);
        $this->assertDatabaseHas('profiles', [
            'nomor_anggota' => 'ANG-00002',
        ]);

        $firstPhysicalUnit = Unit::where('kode_unit', 'SNT-001')->firstOrFail();
        $secondPhysicalUnit = Unit::where('kode_unit', 'SNT-002')->firstOrFail();
        $this->assertSame($firstPhysicalUnit->nama_unit, $secondPhysicalUnit->nama_unit);
        $this->assertCount(2, $firstPhysicalUnit->categories);
    }

    public function test_admin_can_create_categories_units_and_member_accounts(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->post(route('admin.categories.store'), [
            'nama_kategori' => 'Pusaka',
            'deskripsi' => 'Kategori demo',
        ])->assertRedirect(route('admin.categories.index'));

        $category = Category::where('nama_kategori', 'Pusaka')->firstOrFail();

        $this->post(route('admin.units.store'), [
            'kode_unit' => 'SNT-100',
            'nama_unit' => 'Unit Uji',
            'deskripsi' => 'Unit untuk tes CRUD',
            'tarif_per_hari' => 15000,
            'categories' => [$category->id],
        ])->assertRedirect(route('admin.units.index'));

        $unit = Unit::where('kode_unit', 'SNT-100')->firstOrFail();
        $this->assertTrue($unit->categories->contains($category));

        $this->post(route('admin.members.store'), [
            'name' => 'Anggota Dibuat Admin',
            'email' => 'anggota.baru@santika.test',
            'role' => 'admin',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'no_telepon' => '081234567890',
            'alamat' => 'Alamat demo',
        ])->assertRedirect(route('admin.members.index'));

        $member = User::where('email', 'anggota.baru@santika.test')->firstOrFail();
        $this->assertSame('user', $member->role);
        $this->assertNotNull($member->profile);
    }

    public function test_admin_forms_validate_required_unique_and_related_values(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['nama_kategori' => 'Kategori Valid']);
        $this->actingAs($admin);

        $this->from(route('admin.categories.create'))
            ->post(route('admin.categories.store'), ['nama_kategori' => ''])
            ->assertRedirect(route('admin.categories.create'))
            ->assertSessionHasErrors('nama_kategori');

        $this->from(route('admin.categories.create'))
            ->post(route('admin.categories.store'), ['nama_kategori' => 'Kategori Valid'])
            ->assertRedirect(route('admin.categories.create'))
            ->assertSessionHasErrors('nama_kategori');

        $this->from(route('admin.units.create'))
            ->post(route('admin.units.store'), [])
            ->assertRedirect(route('admin.units.create'))
            ->assertSessionHasErrors(['kode_unit', 'nama_unit', 'tarif_per_hari', 'categories']);

        $validUnit = [
            'kode_unit' => 'VAL-001',
            'nama_unit' => 'Unit Validasi',
            'tarif_per_hari' => 10000,
            'categories' => [$category->id],
        ];

        $this->post(route('admin.units.store'), $validUnit)
            ->assertRedirect(route('admin.units.index'));
        $unit = Unit::where('kode_unit', 'VAL-001')->firstOrFail();

        $this->from(route('admin.units.create'))
            ->post(route('admin.units.store'), $validUnit)
            ->assertRedirect(route('admin.units.create'))
            ->assertSessionHasErrors('kode_unit');

        $this->from(route('admin.units.create'))
            ->post(route('admin.units.store'), [
                ...$validUnit,
                'kode_unit' => 'VAL-002',
                'categories' => [999999],
            ])
            ->assertRedirect(route('admin.units.create'))
            ->assertSessionHasErrors('categories.0');

        $this->from(route('admin.units.create'))
            ->post(route('admin.units.store'), [
                ...$validUnit,
                'kode_unit' => 'VAL-002',
                'categories' => [$category->id, $category->id],
            ])
            ->assertRedirect(route('admin.units.create'))
            ->assertSessionHasErrors('categories.1');

        $this->put(route('admin.units.update', $unit), $validUnit)
            ->assertRedirect(route('admin.units.index'));

        $this->from(route('admin.members.create'))
            ->post(route('admin.members.store'), [])
            ->assertRedirect(route('admin.members.create'))
            ->assertSessionHasErrors(['name', 'email', 'password']);

        User::factory()->create([
            'email' => 'email-terpakai@santika.test',
            'role' => 'user',
        ]);

        $this->from(route('admin.members.create'))
            ->post(route('admin.members.store'), [
                'name' => 'Anggota Duplikat',
                'email' => 'email-terpakai@santika.test',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
            ])
            ->assertRedirect(route('admin.members.create'))
            ->assertSessionHasErrors('email');

        $this->from(route('admin.members.create'))
            ->post(route('admin.members.store'), [
                'name' => 'Konfirmasi Salah',
                'email' => 'konfirmasi.salah@santika.test',
                'password' => 'Password123!',
                'password_confirmation' => 'PasswordLain!',
            ])
            ->assertRedirect(route('admin.members.create'))
            ->assertSessionHasErrors('password');
    }

    public function test_admin_category_search_filters_all_records_and_keeps_query_in_pagination(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        for ($index = 1; $index <= 17; $index++) {
            Category::create([
                'nama_kategori' => 'Pencarian Global ' . str_pad((string) $index, 2, '0', STR_PAD_LEFT),
                'deskripsi' => 'Kategori untuk pengujian pencarian',
            ]);
        }
        Category::create(['nama_kategori' => 'Kategori Tidak Terkait']);

        $response = $this->actingAs($admin)->get(route('admin.categories.index', [
            'search' => 'Pencarian Global',
            'page' => 2,
        ]))->assertOk();

        $response->assertSeeText('Pencarian Global 16')
            ->assertSeeText('Pencarian Global 17')
            ->assertDontSeeText('Pencarian Global 01')
            ->assertDontSeeText('Kategori Tidak Terkait');

        $paginationQuery = [];
        parse_str(parse_url($response->viewData('categories')->url(1), PHP_URL_QUERY) ?: '', $paginationQuery);
        $this->assertSame('Pencarian Global', $paginationQuery['search'] ?? null);
    }

    public function test_admin_member_search_matches_profile_fields_across_pages_and_keeps_query(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        for ($index = 1; $index <= 17; $index++) {
            $member = $this->createMember('anggota.search' . $index . '@santika.test');
            $member->update([
                'name' => 'Anggota Global ' . str_pad((string) $index, 2, '0', STR_PAD_LEFT),
            ]);
            $member->profile->update([
                'no_telepon' => '0822-SEARCH-' . str_pad((string) $index, 2, '0', STR_PAD_LEFT),
            ]);
        }
        $unrelatedMember = $this->createMember('anggota.tidak-terkait@santika.test');
        $unrelatedMember->update(['name' => 'Anggota Tidak Terkait']);

        $response = $this->actingAs($admin)->get(route('admin.members.index', [
            'search' => '0822-SEARCH',
            'page' => 2,
        ]))->assertOk();

        $response->assertSeeText('Anggota Global 16')
            ->assertSeeText('Anggota Global 17')
            ->assertDontSeeText('Anggota Global 01')
            ->assertDontSeeText('Anggota Tidak Terkait');

        $paginationQuery = [];
        parse_str(parse_url($response->viewData('members')->url(1), PHP_URL_QUERY) ?: '', $paginationQuery);
        $this->assertSame('0822-SEARCH', $paginationQuery['search'] ?? null);
    }

    public function test_member_can_search_catalog_by_unit_name(): void
    {
        $member = $this->createMember('anggota.katalog@santika.test');
        $matchingUnit = $this->createUnit('CAT-001', 'Keris Pusaka', 'tersedia');
        $otherUnit = $this->createUnit('CAT-002', 'Minyak Pelet', 'tersedia');

        $this->actingAs($member)
            ->get(route('katalog', ['search' => 'Keris']))
            ->assertOk()
            ->assertSeeText($matchingUnit->nama_unit)
            ->assertDontSeeText($otherUnit->nama_unit);
    }

    public function test_member_loan_pages_only_show_their_own_transactions(): void
    {
        $member = $this->createMember('anggota.privasi@santika.test');
        $otherMember = $this->createMember('anggota.lain@santika.test');
        $ownLoan = $this->createActiveLoan($member, 'OWN-001');
        $otherLoan = $this->createActiveLoan($otherMember, 'OTHER-001');

        $this->actingAs($member)
            ->get(route('pinjaman.index'))
            ->assertOk()
            ->assertSeeText($ownLoan->unit->nama_unit)
            ->assertDontSeeText($otherLoan->unit->nama_unit);

        $this->get(route('pinjaman.riwayat'))
            ->assertOk()
            ->assertSeeText($ownLoan->unit->nama_unit)
            ->assertDontSeeText($otherLoan->unit->nama_unit);
    }

    public function test_member_can_update_only_their_profile_and_view_their_loan_pages(): void
    {
        $member = $this->createMember('anggota.profil@santika.test');

        $this->actingAs($member)
            ->put(route('profile.update'), [
                'name' => 'Nama Diperbarui',
                'no_telepon' => '081111111111',
                'alamat' => 'Alamat Baru',
            ])
            ->assertRedirect(route('profile.show'));

        $this->assertDatabaseHas('users', [
            'id' => $member->id,
            'name' => 'Nama Diperbarui',
        ]);
        $this->assertDatabaseHas('profiles', [
            'user_id' => $member->id,
            'alamat' => 'Alamat Baru',
        ]);

        $this->get(route('profile.show'))->assertOk();
        $this->get(route('profile.edit'))->assertOk();
        $this->get(route('pinjaman.index'))->assertOk();
        $this->get(route('pinjaman.riwayat'))->assertOk();
    }

    public function test_member_can_borrow_a_unit_and_admin_can_process_late_return(): void
    {
        config(['santika.denda_per_hari' => 50000]);

        $member = $this->createMember('anggota.pinjam@santika.test');
        $admin = User::factory()->create(['role' => 'admin']);
        $unit = $this->createUnit('SNT-200', 'Unit Pinjam', 'tersedia', 12000);
        $dueDate = Carbon::today()->addDays(5)->toDateString();

        $this->actingAs($member)
            ->post(route('loans.store', $unit))
            ->assertRedirect(route('katalog'));

        $loan = Peminjaman::where('profile_id', $member->profile->id)->firstOrFail();
        $this->assertSame($dueDate, $loan->tanggal_jatuh_tempo->toDateString());
        $this->assertDatabaseHas('units', ['id' => $unit->id, 'status' => 'dipinjam']);

        $loan->update([
            'tanggal_jatuh_tempo' => Carbon::today()->subDays(2)->toDateString(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.returns.index'))
            ->assertOk()
            ->assertSee('Rp100.000')
            ->assertSee('Rp50.000/hari');

        $this->post(route('admin.returns.process', $loan))
            ->assertRedirect(route('admin.returns.index'));

        $loan->refresh();
        $this->assertSame('dikembalikan', $loan->status);
        $this->assertSame(2, $loan->hari_terlambat);
        $this->assertSame(100000, $loan->denda);
        $this->assertDatabaseHas('units', ['id' => $unit->id, 'status' => 'tersedia']);
    }

    public function test_member_cannot_exceed_the_active_loan_limit(): void
    {
        $member = $this->createMember('anggota.batas@santika.test');
        $this->createActiveLoan($member, 'SNT-301');
        $this->createActiveLoan($member, 'SNT-302');
        $thirdUnit = $this->createUnit('SNT-303', 'Unit Ketiga', 'tersedia');

        $this->actingAs($member)
            ->from(route('katalog'))
            ->post(route('loans.store', $thirdUnit))
            ->assertRedirect(route('katalog'))
            ->assertSessionHas('error');

        $this->assertSame(2, $member->profile->jumlahPinjamanAktif());
        $this->assertDatabaseHas('units', ['id' => $thirdUnit->id, 'status' => 'tersedia']);
    }

    private function createMember(string $email): User
    {
        $member = User::factory()->create([
            'email' => $email,
            'role' => 'user',
        ]);

        Profile::create([
            'user_id' => $member->id,
            'nomor_anggota' => 'ANG-' . str_pad((string) $member->id, 5, '0', STR_PAD_LEFT),
            'tanggal_daftar' => now()->toDateString(),
        ]);

        return $member->fresh();
    }

    private function createUnit(string $code, string $name, string $status, int $tariff = 10000): Unit
    {
        return Unit::create([
            'kode_unit' => $code,
            'nama_unit' => $name,
            'status' => $status,
            'tarif_per_hari' => $tariff,
        ]);
    }

    private function createActiveLoan(User $member, string $code): Peminjaman
    {
        $unit = $this->createUnit($code, 'Unit ' . $code, 'dipinjam');

        return Peminjaman::create([
            'profile_id' => $member->profile->id,
            'unit_id' => $unit->id,
            'tanggal_pinjam' => Carbon::today()->toDateString(),
            'tanggal_jatuh_tempo' => Carbon::today()->addDays(5)->toDateString(),
            'status' => 'dipinjam',
        ]);
    }
}
