@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">

    <!-- ===== Sidebar Admin ===== -->
    <aside class="bg-sidebar border-r border-border w-64 shrink-0 hidden lg:flex flex-col md:sticky md:top-0 md:h-screen">
        <div class="p-6 border-b border-border">
            <div class="flex items-center gap-3">
                <span class="text-3xl animate-float inline-block">👻</span>
                <div>
                    <h2 class="font-display text-2xl text-primary leading-none">Gaib Admin</h2>
                    <p class="text-foreground/40 text-xs mt-1">Panel Kendali</p>
                </div>
            </div>
        </div>
        <nav class="flex-1 p-4 space-y-1">
            <a href="/admin" class="flex items-center gap-3 px-4 py-3 rounded-xl text-foreground/60 hover:text-foreground hover:bg-muted transition-all">
                <span class="text-lg">📋</span>
                <span class="text-sm font-medium">Daftar Unit</span>
            </a>
            <a href="/admin/create" class="flex items-center gap-3 px-4 py-3 rounded-xl text-foreground/60 hover:text-foreground hover:bg-muted transition-all">
                <span class="text-lg">➕</span>
                <span class="text-sm font-medium">Tambah Unit</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-foreground/60 hover:text-foreground hover:bg-muted transition-all">
                <span class="text-lg">🔮</span>
                <span class="text-sm font-medium">Kategori</span>
            </a>
            <!-- Active State: Pengembalian -->
            <a href="/admin/pengembalian" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/15 text-primary border border-primary/30 transition-all">
                <span class="text-lg">📦</span>
                <span class="text-sm font-medium">Pengembalian</span>
            </a>
        </nav>
        <div class="p-4 border-t border-border">
            <div class="flex items-center gap-3 px-2">
                <div class="w-10 h-10 rounded-full bg-primary/20 border border-primary/40 flex items-center justify-center text-primary text-lg">🧙</div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-foreground truncate">Dukun Master</p>
                    <p class="text-xs text-foreground/40 truncate">admin@gaib.id</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- ===== Main Content ===== -->
    <main class="flex-1 p-6 sm:p-10 overflow-x-auto">

        <div class="mb-8">
            <h1 class="font-display text-3xl sm:text-4xl text-primary tracking-wide">Pantau Pengembalian & Denda</h1>
            <p class="text-foreground/50 mt-2 text-sm">Pantau semua peminjaman unit gaib yang aktif dan kelola denda keterlambatan.</p>
        </div>

        <!-- Validasi Sukses -->
        @if(session('success'))
            <div class="mb-5 rounded-lg border border-success/50 bg-success/20 p-4 text-sm text-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-glass backdrop-blur-xl border border-border rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-foreground/50">Sedang Dipinjam</p>
                    <span class="text-xl">📦</span>
                </div>
                <p class="text-3xl font-bold text-foreground mt-2">{{ $statDipinjam ?? 0 }}</p>
            </div>
            <div class="bg-glass backdrop-blur-xl border border-border rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-foreground/50">Terlambat</p>
                    <span class="text-xl">⚠️</span>
                </div>
                <p class="text-3xl font-bold text-destructive mt-2">{{ $statTerlambat ?? 0 }}</p>
            </div>
            <div class="bg-glass backdrop-blur-xl border border-border rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-foreground/50">Total Potensi Denda</p>
                    <span class="text-xl">💰</span>
                </div>
                <p class="text-3xl font-bold text-destructive mt-2">Rp {{ number_format($statDenda ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-glass backdrop-blur-xl border border-border rounded-3xl glow-primary p-6 sm:p-8 overflow-x-auto">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="font-display text-2xl text-primary">Daftar Pengembalian Aktif</h2>
                    <p class="text-foreground/40 text-xs mt-1">Klik "Proses Kembali" untuk menandai unit sebagai dikembalikan.</p>
                </div>
                <span class="text-2xl">📜</span>
            </div>

            <!-- Data Table -->
            <div class="overflow-x-auto rounded-xl border border-border">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-muted border-b border-border">
                            <th class="px-4 py-3.5 text-xs font-semibold text-foreground/60 uppercase tracking-wider whitespace-nowrap">Peminjam</th>
                            <th class="px-4 py-3.5 text-xs font-semibold text-foreground/60 uppercase tracking-wider whitespace-nowrap">Unit</th>
                            <th class="px-4 py-3.5 text-xs font-semibold text-foreground/60 uppercase tracking-wider whitespace-nowrap">Tgl Pinjam</th>
                            <th class="px-4 py-3.5 text-xs font-semibold text-foreground/60 uppercase tracking-wider whitespace-nowrap">Jatuh Tempo</th>
                            <th class="px-4 py-3.5 text-xs font-semibold text-foreground/60 uppercase tracking-wider whitespace-nowrap text-center">Hari Telat</th>
                            <th class="px-4 py-3.5 text-xs font-semibold text-foreground/60 uppercase tracking-wider whitespace-nowrap">Denda (Rp)</th>
                            <th class="px-4 py-3.5 text-xs font-semibold text-foreground/60 uppercase tracking-wider whitespace-nowrap">Status</th>
                            <th class="px-4 py-3.5 text-xs font-semibold text-foreground/60 uppercase tracking-wider whitespace-nowrap text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($peminjaman as $item)
                            <tr class="hover:bg-muted/50 transition-colors">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-primary/20 border border-primary/30 flex items-center justify-center text-sm shrink-0">{{ $item->emoji_user ?? '👤' }}</div>
                                        <span class="text-sm text-foreground/90 whitespace-nowrap">{{ $item->nama_peminjam }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm text-foreground/90 whitespace-nowrap">{{ $item->nama_unit }}</td>
                                <td class="px-4 py-4 text-sm text-foreground/60 whitespace-nowrap">{{ $item->tgl_pinjam }}</td>
                                <td class="px-4 py-4 text-sm text-foreground/60 whitespace-nowrap">{{ $item->jatuh_tempo }}</td>
                                
                                <!-- Logika Kondisional untuk Denda -->
                                @if($item->hari_terlambat > 0)
                                    <td class="px-4 py-4 text-sm font-semibold text-center text-destructive whitespace-nowrap">{{ $item->hari_terlambat }}</td>
                                    <td class="px-4 py-4 text-sm font-bold text-destructive whitespace-nowrap">Rp {{ number_format($item->denda, 0, ',', '.') }}</td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-destructive/15 text-destructive border border-destructive/30 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-destructive"></span>Terlambat
                                        </span>
                                    </td>
                                @else
                                    <td class="px-4 py-4 text-sm font-semibold text-center text-foreground/60 whitespace-nowrap">0</td>
                                    <td class="px-4 py-4 text-sm text-foreground/60 whitespace-nowrap">Rp 0</td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-primary/15 text-primary border border-primary/30 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>Sedang Dipinjam
                                        </span>
                                    </td>
                                @endif

                                <!-- Form Aksi -->
                                <td class="px-4 py-4 text-center">
                                    <form action="#" method="POST" onsubmit="return confirm('Proses pengembalian unit ini?');">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 rounded-lg bg-primary hover:bg-primary/90 text-white text-xs font-semibold whitespace-nowrap transition-all hover:glow-primary active:scale-[0.97] focus:outline-none focus:ring-2 focus:ring-primary/40">
                                            Proses Kembali
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-sm text-foreground/50">Belum ada peminjaman yang aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection