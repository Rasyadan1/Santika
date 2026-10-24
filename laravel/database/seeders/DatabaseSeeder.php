<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Profile;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $demoPassword = config('santika.demo_password', 'password');

        User::firstOrCreate(
            ['email' => 'admin@santika.test'],
            [
                'name' => 'Admin Santika',
                'password' => $demoPassword,
                'role' => 'admin',
            ]
        );

        $members = [
            [
                'name' => 'Anggota Demo',
                'email' => 'anggota@santika.test',
                'phone' => '081234567890',
                'address' => 'Alamat demo anggota Santika',
            ],
            [
                'name' => 'Anggota Kedua',
                'email' => 'anggota2@santika.test',
                'phone' => '081234567891',
                'address' => 'Alamat demo anggota kedua',
            ],
        ];

        foreach ($members as $memberData) {
            $member = User::firstOrCreate(
                ['email' => $memberData['email']],
                [
                    'name' => $memberData['name'],
                    'password' => $demoPassword,
                    'role' => 'user',
                ]
            );

            Profile::firstOrCreate(
                ['user_id' => $member->id],
                [
                    'nomor_anggota' => 'ANG-' . str_pad((string) $member->id, 5, '0', STR_PAD_LEFT),
                    'no_telepon' => $memberData['phone'],
                    'alamat' => $memberData['address'],
                    'tanggal_daftar' => now()->toDateString(),
                ]
            );
        }

        $categories = [];
        foreach ([
            'Pusaka' => 'Perlengkapan pusaka dan benda bertuah.',
            'Santet' => 'Perlengkapan ritual fiktif bertema santet.',
            'Pelet' => 'Perlengkapan ritual fiktif bertema pelet.',
        ] as $name => $description) {
            $categories[$name] = Category::firstOrCreate(
                ['nama_kategori' => $name],
                ['deskripsi' => $description]
            );
        }

        $units = [
            [
                'kode_unit' => 'SNT-001',
                'nama_unit' => 'Keris Pusaka Santika',
                'deskripsi' => 'Unit demo untuk mencoba proses peminjaman.',
                'tarif_per_hari' => 10000,
                'categories' => ['Pusaka', 'Santet'],
            ],
            [
                'kode_unit' => 'SNT-002',
                'nama_unit' => 'Keris Pusaka Santika',
                'deskripsi' => 'Contoh nama unit sama dengan kode fisik berbeda.',
                'tarif_per_hari' => 12000,
                'categories' => ['Pusaka'],
            ],
            [
                'kode_unit' => 'SNT-003',
                'nama_unit' => 'Minyak Pelet Demo',
                'deskripsi' => 'Contoh perlengkapan kategori pelet.',
                'tarif_per_hari' => 10000,
                'categories' => ['Pelet'],
            ],
        ];

        foreach ($units as $unitData) {
            $categoryNames = $unitData['categories'];
            unset($unitData['categories']);

            $unit = Unit::firstOrCreate(
                ['kode_unit' => $unitData['kode_unit']],
                $unitData
            );

            $categoryIds = collect($categoryNames)
                ->map(fn (string $name) => $categories[$name]->id)
                ->all();

            $unit->categories()->syncWithoutDetaching($categoryIds);
        }
    }
}
