@extends('layouts.app')

@section('content')
<div class="flex min-h-screen flex-col bg-mystic md:flex-row">
    @include('partials.admin-navigation')

    <main class="min-w-0 flex-1 p-4 pb-12 md:p-8">
        @if (session('success'))
            <div class="mb-4 rounded-lg border border-success/50 bg-success/10 p-3 text-sm text-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg border border-destructive/50 bg-destructive/10 p-3 text-sm text-destructive">{{ session('error') }}</div>
        @endif

        <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-primary">Administrasi</p>
                <h1 class="mt-1 text-2xl font-bold md:text-3xl">Pengembalian Aktif</h1>
                <p class="mt-1 text-sm text-muted-foreground">Pantau pinjaman, keterlambatan, dan estimasi denda hari ini.</p>
            </div>
            <a href="{{ route('admin.returns.riwayat') }}" class="rounded-lg border border-border bg-card px-4 py-2.5 text-sm font-semibold transition hover:border-primary/60 hover:text-primary">Lihat Riwayat</a>
        </div>

        <section class="overflow-hidden rounded-xl border border-border bg-card">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-border px-5 py-4">
                <div>
                    <h2 class="font-semibold">Daftar pinjaman yang belum dikembalikan</h2>
                    <p class="mt-1 text-xs text-muted-foreground">Hari terlambat dan denda dihitung ulang berdasarkan tanggal hari ini.</p>
                </div>
                <span class="rounded-full bg-primary/15 px-3 py-1 text-xs font-semibold text-primary">{{ $pinjamanAktif->total() }} pinjaman</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[980px] text-left text-sm">
                    <thead class="border-b border-border bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground">
                        <tr>
                            <th class="px-5 py-3">Anggota</th>
                            <th class="px-5 py-3">Unit</th>
                            <th class="px-5 py-3">Tanggal Pinjam</th>
                            <th class="px-5 py-3">Jatuh Tempo</th>
                            <th class="px-5 py-3 text-center">Terlambat</th>
                            <th class="px-5 py-3">Estimasi Denda</th>
                            <th class="px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pinjamanAktif as $pinjaman)
                            @php
                                $hariTerlambat = $pinjaman->hitungHariTerlambat(now());
                                $dendaPerHari = (int) config('santika.denda_per_hari', 50000);
                                $estimasiDenda = $hariTerlambat * $dendaPerHari;
                            @endphp
                            <tr class="border-b border-border last:border-0 transition hover:bg-muted/30">
                                <td class="px-5 py-4">
                                    <p class="font-medium">{{ $pinjaman->profile?->user?->name ?? 'Anggota tidak ditemukan' }}</p>
                                    <p class="mt-1 text-xs text-muted-foreground">{{ $pinjaman->profile?->nomor_anggota ?? '—' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="font-medium">{{ $pinjaman->unit?->nama_unit ?? 'Unit tidak ditemukan' }}</p>
                                    <p class="mt-1 font-mono text-xs text-muted-foreground">{{ $pinjaman->unit?->kode_unit ?? '—' }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-muted-foreground">{{ $pinjaman->tanggal_pinjam?->format('d M Y') ?? '—' }}</td>
                                <td class="whitespace-nowrap px-5 py-4 text-muted-foreground">{{ $pinjaman->tanggal_jatuh_tempo?->format('d M Y') ?? '—' }}</td>
                                <td class="px-5 py-4 text-center">
                                    @if ($hariTerlambat > 0)
                                        <span class="rounded-full bg-destructive/15 px-2.5 py-1 text-xs font-semibold text-destructive">{{ $hariTerlambat }} hari</span>
                                    @else
                                        <span class="rounded-full bg-success/15 px-2.5 py-1 text-xs font-semibold text-success">Tepat waktu</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <p class="font-semibold {{ $estimasiDenda > 0 ? 'text-destructive' : '' }}">Rp{{ number_format($estimasiDenda, 0, ',', '.') }}</p>
                                    <p class="mt-1 text-xs text-muted-foreground">Rp{{ number_format($dendaPerHari, 0, ',', '.') }}/hari</p>
                                </td>
                                <td class="px-5 py-4">
                                    <form action="{{ route('admin.returns.process', $pinjaman) }}" method="POST" onsubmit="return confirm('Proses pengembalian unit ini? Tindakan ini akan memperbarui status pinjaman dan menghitung denda final.');">
                                        @csrf
                                        <button type="submit" class="whitespace-nowrap rounded-lg bg-primary px-3.5 py-2 text-xs font-semibold text-primary-foreground transition hover:brightness-110">Proses Pengembalian</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <p class="text-2xl">✅</p>
                                    <p class="mt-2 font-medium">Tidak ada pinjaman aktif</p>
                                    <p class="mt-1 text-sm text-muted-foreground">Semua unit telah dikembalikan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
        <div class="mt-4">{{ $pinjamanAktif->links() }}</div>
    </main>
</div>
@endsection
