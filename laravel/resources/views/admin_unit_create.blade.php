@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">

    <!-- ===== Sidebar Admin (Sama dengan admin.blade.php) ===== -->
    <aside class="border-b border-sidebar-border bg-sidebar md:sticky md:top-0 md:h-screen md:w-64 md:border-r md:border-b-0 hidden lg:flex flex-col">
        <div class="px-6 py-5 border-b border-border">
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
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/15 text-primary border border-primary/30 transition-all">
                <span class="text-lg">➕</span>
                <span class="text-sm font-medium">Tambah Unit</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-foreground/60 hover:text-foreground hover:bg-muted transition-all">
                <span class="text-lg">🔮</span>
                <span class="text-sm font-medium">Kategori</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-foreground/60 hover:text-foreground hover:bg-muted transition-all">
                <span class="text-lg">📜</span>
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

    <!-- ===== Main Content (Form Tambah Unit) ===== -->
    <main class="flex-1 p-6 sm:p-10">

        <!-- Page heading -->
        <div class="mb-8">
            <h1 class="font-display text-3xl sm:text-4xl text-primary tracking-wide">Tambah Unit Gaib Baru</h1>
            <p class="text-foreground/50 mt-2 text-sm">Isi mantra berikut untuk mendaftarkan unit gaib baru ke dalam inventaris.</p>
        </div>

        <!-- Validasi Error -->
        @if ($errors->any())
            <div class="mb-5 max-w-3xl rounded-lg border border-destructive/50 bg-destructive/20 p-4">
                <ul class="list-disc pl-5 text-sm text-destructive-foreground">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-glass backdrop-blur-xl border border-border rounded-3xl glow-primary p-6 sm:p-8 max-w-3xl">

            <form class="space-y-6" action="#" method="POST">
                @csrf

                <!-- Nama Unit & Kode Unit -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="nama_unit" class="block text-sm font-medium text-foreground/80 mb-2">Nama Unit</label>
                        <input id="nama_unit" name="nama_unit" type="text" value="{{ old('nama_unit') }}" placeholder="Contoh: Santet Sepi Malam" required class="w-full bg-input border border-border rounded-xl px-4 py-3 text-foreground placeholder-foreground/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all">
                    </div>
                    <div>
                        <label for="kode_unit" class="block text-sm font-medium text-foreground/80 mb-2">Kode Unit</label>
                        <input id="kode_unit" name="kode_unit" type="text" value="{{ old('kode_unit') }}" placeholder="Contoh: ST-001" required class="w-full bg-input border border-border rounded-xl px-4 py-3 text-foreground placeholder-foreground/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all">
                    </div>
                </div>

                <!-- Kategori (Checkboxes) -->
                <div>
                    <label class="block text-sm font-medium text-foreground/80 mb-3">Kategori</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center gap-3 bg-input border border-border rounded-xl px-4 py-3 cursor-pointer hover:border-primary/50 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/10">
                            <input type="checkbox" name="kategori[]" value="Santet" class="w-4 h-4 accent-primary">
                            <span class="text-sm text-foreground/80">Santet</span>
                        </label>
                        <label class="flex items-center gap-3 bg-input border border-border rounded-xl px-4 py-3 cursor-pointer hover:border-primary/50 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/10">
                            <input type="checkbox" name="kategori[]" value="Pelet" class="w-4 h-4 accent-primary">
                            <span class="text-sm text-foreground/80">Pelet</span>
                        </label>
                        <label class="flex items-center gap-3 bg-input border border-border rounded-xl px-4 py-3 cursor-pointer hover:border-primary/50 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/10">
                            <input type="checkbox" name="kategori[]" value="Pusaka" class="w-4 h-4 accent-primary">
                            <span class="text-sm text-foreground/80">Pusaka</span>
                        </label>
                        <label class="flex items-center gap-3 bg-input border border-border rounded-xl px-4 py-3 cursor-pointer hover:border-primary/50 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/10">
                            <input type="checkbox" name="kategori[]" value="Jimat" class="w-4 h-4 accent-primary">
                            <span class="text-sm text-foreground/80">Jimat</span>
                        </label>
                    </div>
                </div>

                <!-- Status (Dropdown) -->
                <div>
                    <label for="status" class="block text-sm font-medium text-foreground/80 mb-2">Status Tersedia</label>
                    <div class="relative">
                        <select id="status" name="status" required class="w-full bg-input border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all appearance-none cursor-pointer">
                            <option value="Tersedia" class="bg-sidebar">Tersedia</option>
                            <option value="Dipinjam" class="bg-sidebar">Dipinjam</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-foreground/50">▼</div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-foreground/80 mb-2">Deskripsi Khasiat</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan kekuatan dan pantangan alat gaib ini..." required class="w-full bg-input border border-border rounded-xl px-4 py-3 text-foreground placeholder-foreground/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all resize-none">{{ old('deskripsi') }}</textarea>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 pt-2">
                    <a href="/admin" class="px-6 py-3 rounded-xl border border-border text-foreground/70 hover:text-foreground hover:bg-muted font-medium text-sm transition-all active:scale-[0.98]">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 rounded-xl bg-primary hover:bg-primary/90 text-white font-semibold text-sm transition-all hover:glow-primary active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-primary/40">
                        Simpan Mantra
                    </button>
                </div>
            </form>

        </div>
    </main>
</div>
@endsection