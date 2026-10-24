@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-mystic pb-12">
    @include('partials.member-navigation')

    <main class="mx-auto max-w-5xl px-4 py-10">
        <header class="mb-8">
            <p class="text-sm text-muted-foreground">Ruang pribadi anggota</p>
            <h1 class="mt-1 font-display text-4xl tracking-wide text-primary">Profil Anggota</h1>
            <p class="mt-2 text-muted-foreground">Kelola informasi akun dan pantau riwayat pinjaman Anda.</p>
        </header>

        @if (session('success'))
            <div class="mb-5 rounded-lg border border-success/50 bg-success/10 p-4 text-sm text-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-5 rounded-lg border border-destructive/50 bg-destructive/10 p-4 text-sm text-destructive">{{ session('error') }}</div>
        @endif

        <section class="rounded-2xl border border-border bg-glass p-6 backdrop-blur-xl sm:p-8">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full border border-primary/40 bg-primary/20 text-3xl">🧙</div>
                    <div>
                        <h2 class="text-2xl font-semibold">{{ $user->name }}</h2>
                        <p class="text-sm text-muted-foreground">{{ $user->email }}</p>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground transition hover:brightness-110">Ubah Profil</a>
            </div>

            <div class="mt-8 grid gap-5 border-t border-border pt-6 sm:grid-cols-2">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Nomor Anggota</p>
                    <p class="mt-1">{{ $profile?->nomor_anggota ?: 'Belum tersedia' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">No. Telepon</p>
                    <p class="mt-1">{{ $profile?->no_telepon ?: 'Belum diisi' }}</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Alamat</p>
                    <p class="mt-1 whitespace-pre-line">{{ $profile?->alamat ?: 'Belum diisi' }}</p>
                </div>
                @if ($profile?->tanggal_daftar)
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Terdaftar Sejak</p>
                        <p class="mt-1">{{ $profile->tanggal_daftar->format('d M Y') }}</p>
                    </div>
                @endif
            </div>

            @unless ($profile)
                <p class="mt-6 rounded-xl border border-primary/30 bg-primary/10 p-4 text-sm text-muted-foreground">Data profil anggota belum tersedia. Anda dapat melengkapinya melalui Ubah Profil.</p>
            @endunless
        </section>

        <section class="mt-6 grid gap-4 sm:grid-cols-2">
            <a href="{{ route('pinjaman.index') }}" class="rounded-2xl border border-border bg-card p-5 transition hover:border-primary/50 hover:glow-primary">
                <span class="text-2xl">🔮</span>
                <h2 class="mt-3 font-semibold">Pinjaman Aktif</h2>
                <p class="mt-1 text-sm text-muted-foreground">Lihat unit yang sedang Anda pinjam.</p>
            </a>
            <a href="{{ route('pinjaman.riwayat') }}" class="rounded-2xl border border-border bg-card p-5 transition hover:border-primary/50 hover:glow-primary">
                <span class="text-2xl">📜</span>
                <h2 class="mt-3 font-semibold">Riwayat Pinjaman</h2>
                <p class="mt-1 text-sm text-muted-foreground">Tinjau pinjaman Anda sebelumnya.</p>
            </a>
        </section>

        <a href="{{ route('katalog') }}" class="mt-6 inline-flex text-sm font-medium text-primary transition hover:underline">← Kembali ke katalog</a>
    </main>
</div>
@endsection
