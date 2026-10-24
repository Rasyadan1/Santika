<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Peminjaman;
use App\Models\Profile;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_only_reach_the_login_page(): void
    {
        $this->get('/')->assertRedirect(route('login'));
        $this->get('/login')->assertOk();
        $this->get('/katalog')->assertRedirect(route('login'));
        $this->get('/admin')->assertRedirect(route('login'));
        $this->get('/register')->assertNotFound();
        $this->post(route('admin.categories.store'), [])->assertRedirect(route('login'));
    }

    public function test_member_can_open_catalog_but_not_admin_pages(): void
    {
        $member = $this->createMember();

        $this->actingAs($member)->get('/katalog')->assertOk();
        $this->actingAs($member)->get('/profile')->assertOk();
        $this->actingAs($member)->get('/pinjaman-saya')->assertOk();
        $this->actingAs($member)->get('/admin')->assertForbidden();
        $this->actingAs($member)->get('/admin/units')->assertForbidden();
        $this->actingAs($member)->get('/admin/returns')->assertForbidden();
    }

    public function test_role_middleware_blocks_admin_mutations_and_member_actions(): void
    {
        $member = $this->createMember();
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['nama_kategori' => 'Kategori Aman']);
        $unit = Unit::create([
            'kode_unit' => 'SEC-001',
            'nama_unit' => 'Unit Aman',
            'status' => 'dipinjam',
            'tarif_per_hari' => 10000,
        ]);
        $loan = Peminjaman::create([
            'profile_id' => $member->profile->id,
            'unit_id' => $unit->id,
            'tanggal_pinjam' => now()->toDateString(),
            'tanggal_jatuh_tempo' => now()->addDays(5)->toDateString(),
            'status' => 'dipinjam',
        ]);

        $this->actingAs($member);

        $this->post(route('admin.categories.store'), [
            'nama_kategori' => 'Tidak Boleh Dibuat',
        ])->assertForbidden();
        $this->put(route('admin.categories.update', $category), [
            'nama_kategori' => 'Kategori Diubah',
        ])->assertForbidden();
        $this->post(route('admin.units.store'), [
            'kode_unit' => 'SEC-002',
            'nama_unit' => 'Unit Baru',
            'tarif_per_hari' => 10000,
            'categories' => [$category->id],
        ])->assertForbidden();
        $this->post(route('admin.members.store'), [
            'name' => 'Tidak Boleh Dibuat',
            'email' => 'bukan-anggota@santika.test',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ])->assertForbidden();
        $this->post(route('admin.returns.process', $loan))->assertForbidden();

        $this->actingAs($admin)
            ->post(route('loans.store', $unit))
            ->assertForbidden();

        $this->assertDatabaseMissing('categories', ['nama_kategori' => 'Tidak Boleh Dibuat']);
        $this->assertDatabaseMissing('units', ['kode_unit' => 'SEC-002']);
        $this->assertDatabaseMissing('users', ['email' => 'bukan-anggota@santika.test']);
        $this->assertDatabaseHas('peminjaman', [
            'id' => $loan->id,
            'status' => 'dipinjam',
        ]);
    }

    public function test_member_navigation_is_complete_and_marks_the_current_page(): void
    {
        $member = $this->createMember();
        $this->actingAs($member);

        $pages = [
            route('katalog'),
            route('pinjaman.index'),
            route('pinjaman.riwayat'),
            route('profile.show'),
            route('profile.edit'),
        ];

        foreach ($pages as $url) {
            $response = $this->get($url)->assertOk();

            $response->assertSeeText('Katalog')
                ->assertSeeText('Pinjaman')
                ->assertSeeText('Riwayat')
                ->assertSeeText('Profil')
                ->assertSee('aria-current="page"', false)
                ->assertSee('bg-primary/20 text-primary', false);
        }
    }

    public function test_admin_can_open_admin_dashboard_but_cannot_use_member_catalog(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->actingAs($admin)->get('/admin/units')->assertOk();
        $this->actingAs($admin)->get('/admin/categories')->assertOk();
        $this->actingAs($admin)->get('/admin/members')->assertOk();
        $this->actingAs($admin)->get('/admin/returns')->assertOk();
        $this->actingAs($admin)->get('/katalog')->assertForbidden();
    }

    public function test_admin_forms_history_and_member_report_render(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = $this->createMember();
        $category = Category::create(['nama_kategori' => 'Kategori Uji']);
        $unit = Unit::create([
            'kode_unit' => 'UAT-001',
            'nama_unit' => 'Unit Uji',
            'status' => 'tersedia',
            'tarif_per_hari' => 10000,
        ]);

        $this->actingAs($admin);

        $this->get(route('admin.units.create'))->assertOk();
        $this->get(route('admin.units.edit', $unit))->assertOk();
        $this->get(route('admin.categories.create'))->assertOk();
        $this->get(route('admin.categories.edit', $category))->assertOk();
        $this->get(route('admin.members.create'))->assertOk();
        $this->get(route('admin.members.edit', $member))->assertOk();
        $this->get(route('admin.returns.riwayat'))->assertOk();
        $this->get(route('admin.returns.print', $member))->assertOk();
    }

    public function test_admin_navigation_is_consistent_and_marks_the_current_section(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $member = $this->createMember();
        $category = Category::create(['nama_kategori' => 'Kategori Navigasi']);
        $unit = Unit::create([
            'kode_unit' => 'NAV-001',
            'nama_unit' => 'Unit Navigasi',
            'status' => 'tersedia',
            'tarif_per_hari' => 10000,
        ]);

        $pages = [
            [route('admin.dashboard'), route('admin.dashboard')],
            [route('admin.units.index'), route('admin.units.index')],
            [route('admin.units.create'), route('admin.units.index')],
            [route('admin.units.edit', $unit), route('admin.units.index')],
            [route('admin.categories.index'), route('admin.categories.index')],
            [route('admin.categories.create'), route('admin.categories.index')],
            [route('admin.categories.edit', $category), route('admin.categories.index')],
            [route('admin.members.index'), route('admin.members.index')],
            [route('admin.members.create'), route('admin.members.index')],
            [route('admin.members.edit', $member), route('admin.members.index')],
            [route('admin.returns.index'), route('admin.returns.index')],
            [route('admin.returns.riwayat'), route('admin.returns.riwayat')],
            [route('admin.returns.print', $member), route('admin.returns.riwayat')],
        ];

        $this->actingAs($admin);

        foreach ($pages as [$url, $activeLink]) {
            $response = $this->get($url)->assertOk();

            $response->assertSeeText('Dashboard')
                ->assertSeeText('Kelola Data')
                ->assertSee('<details', false)
                ->assertDontSee('<details open', false)
                ->assertSee('sessionStorage', false)
                ->assertSee('santika-admin-data-menu-open', false)
                ->assertSeeText('Unit')
                ->assertSeeText('Kategori')
                ->assertSeeText('Anggota')
                ->assertSeeText('Pengembalian')
                ->assertSeeText('Riwayat Peminjaman')
                ->assertSeeText('Keluar');

            $this->assertMatchesRegularExpression(
                '/href="' . preg_quote($activeLink, '/') . '"\s+aria-current="page"/',
                $response->getContent(),
            );
        }
    }

    public function test_user_without_member_profile_cannot_access_member_pages(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get('/katalog')->assertForbidden();
    }

    public function test_authenticated_user_can_log_out(): void
    {
        $member = $this->createMember();

        $this->actingAs($member)
            ->post('/logout')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    private function createMember(): User
    {
        $member = User::factory()->create(['role' => 'user']);

        Profile::create([
            'user_id' => $member->id,
            'nomor_anggota' => 'ANG-' . str_pad((string) $member->id, 5, '0', STR_PAD_LEFT),
            'tanggal_daftar' => now()->toDateString(),
        ]);

        return $member;
    }
}
