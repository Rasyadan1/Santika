@extends('layouts.app')

@section('content')
<!-- ===== Top Navbar ===== -->
<header class="sticky top-0 z-50 bg-glass backdrop-blur-xl border-b border-border">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center gap-2">
                <span class="text-2xl animate-float inline-block">👻</span>
                <span class="font-display text-2xl text-primary tracking-wide hidden sm:inline">SANTIKA</span>
            </div>

            <!-- Nav links -->
            <nav class="hidden md:flex items-center gap-1">
                <a href="/katalog" class="px-4 py-2 rounded-lg text-foreground/60 hover:text-foreground hover:bg-muted text-sm font-medium transition-all">Katalog Unit</a>
                <a href="/profil" class="px-4 py-2 rounded-lg bg-primary/15 text-primary border border-primary/30 text-sm font-medium transition-all">Profil & Riwayat</a>
            </nav>

            <!-- User badge -->
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-primary/20 border border-primary/40 flex items-center justify-center text-lg">🧙</div>
                <a href="/" class="text-sm text-destructive hover:underline hidden sm:inline">Logout</a>
            </div>
        </div>
    </div>
</header>

<!-- ===== Main Content ===== -->
<main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">

    <!-- Page heading -->
    <div class="mb-8">
        <h1 class="font-display text-3xl sm:text-4xl text-primary tracking-wide">Profil Anggota</h1>
        <p class="text-foreground/50 mt-2 text-sm">Kelola data pribadi dan lihat riwayat peminjaman unit gaib Anda</p>
    </div>

    <!-- Validasi Error / Success -->
    @if(session('success'))
        <div class="mb-5 rounded-lg border border-success/50 bg-success/20 p-4 text-sm text-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-5 rounded-lg border border-destructive/50 bg-destructive/20 p-4">
            <ul class="list-disc pl-5 text-sm text-destructive-foreground">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- ===== Left: Profil Anggota ===== -->
        <div class="lg:col-span-1">
            <div class="bg-glass backdrop-blur-xl border border-border rounded-3xl glow-primary p-6 sm:p-8 lg:sticky lg:top-24">
                <!-- Avatar -->
                <div class="flex flex-col items-center mb-6">
                    <div class="w-24 h-24 rounded-full bg-primary/15 border-2 border-primary/40 flex items-center justify-center text-5xl mb-4">🧙</div>
                    <h2 class="font-display text-2xl text-primary">{{ Auth::user()->name ?? 'Bang Sapri' }}</h2>
                    <p class="text-foreground/40 text-xs mt-1">Anggota Aktif</p>
                </div>

                <!-- Editable form -->
                <form class="space-y-5" action="#" method="POST">
                    @csrf
                    @method('PUT') <!-- Method standar Laravel untuk Update Data -->

                    <div>
                        <label for="nama" class="block text-sm font-medium text-foreground/80 mb-2">Nama Lengkap</label>
                        <input id="nama" name="name" type="text" value="{{ old('name', 'Bang Sapri') }}" class="w-full bg-input border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all">
                    </div>

                    <div>
                        <label for="telepon" class="block text-sm font-medium text-foreground/80 mb-2">No. Telepon</label>
                        <input id="telepon" name="no_telepon" type="tel" value="{{ old('no_telepon', '0812-3456-7890') }}" class="w-full bg-input border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all">
                    </div>

                    <div>
                        <label for="alamat" class="block text-sm font-medium text-foreground/80 mb-2">Alamat Lengkap</label>
                        <textarea id="alamat" name="alamat" rows="3" class="w-full bg-input border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all resize-none">{{ old('alamat', 'Jl. Makam Keramat No. 13, Kelurahan Gaib') }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-semibold text-sm rounded-xl py-3 transition-all hover:glow-primary active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-primary/40">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <!-- ===== Right: Riwayat Peminjaman ===== -->
        <div class="lg:col-span-2">
            <div class="bg-glass backdrop-blur-xl border border-border rounded-3xl p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="font-display text-2xl text-primary">Riwayat Peminjaman</h2>
                        <p class="text-foreground/40 text-xs mt-1">Daftar unit gaib yang pernah Anda pinjam</p>
                    </div>
                    <span class="text-2xl">📜</span>
                </div>

                <!-- Table Riwayat -->
                <div class="overflow-x-auto rounded-xl border border-border">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-muted border-b border-border">
                                <th class="px-4 py-3 text-xs font-semibold text-foreground/60 uppercase tracking-wider">Nama Unit</th>
                                <th class="px-4 py-3 text-xs font-semibold text-foreground/60 uppercase tracking-wider whitespace-nowrap">Tgl Pinjam</th>
                                <th class="px-4 py-3 text-xs font-semibold text-foreground/60 uppercase tracking-wider whitespace-nowrap">Jatuh Tempo</th>
                                <th class="px-4 py-3 text-xs font-semibold text-foreground/60 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @forelse($riwayat ?? [] as $item)
                                <tr class="hover:bg-muted/50 transition-colors">
                                    <td class="px-4 py-4 text-sm text-foreground/90">{{ $item->nama_unit }}</td>
                                    <td class="px-4 py-4 text-sm text-foreground/60 whitespace-nowrap">{{ $item->tgl_pinjam }}</td>
                                    <td class="px-4 py-4 text-sm text-foreground/60 whitespace-nowrap">{{ $item->jatuh_tempo }}</td>
                                    <td class="px-4 py-4">
                                        @if($item->status == 'Sedang Dipinjam')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-primary/15 text-primary border border-primary/30">
                                                <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>Sedang Dipinjam
                                            </span>
                                        @elseif($item->status == 'Dikembalikan')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-success/15 text-success border border-success/30">
                                                <span class="w-1.5 h-1.5 rounded-full bg-success"></span>Dikembalikan
                                            </span>
                                        @elseif($item->status == 'Terlambat')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-destructive/15 text-destructive border border-destructive/30">
                                                <span class="w-1.5 h-1.5 rounded-full bg-destructive"></span>Terlambat
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-sm text-foreground/50">
                                        Belum ada riwayat ritual peminjaman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection