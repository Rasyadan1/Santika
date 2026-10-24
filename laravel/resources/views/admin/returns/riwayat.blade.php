@extends('layouts.app')

@section('content')
<div class="flex min-h-screen flex-col bg-mystic md:flex-row">
    @include('partials.admin-navigation')

    <main class="min-w-0 flex-1 p-4 pb-12 md:p-8">
        <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-primary">Administrasi</p>
                <h1 class="mt-1 text-2xl font-bold md:text-3xl">Riwayat Peminjaman</h1>
                <p class="mt-1 text-sm text-muted-foreground">Telusuri transaksi peminjaman dan pengembalian anggota.</p>
            </div>
            <a href="{{ route('admin.returns.index') }}" class="rounded-lg border border-border bg-card px-4 py-2.5 text-sm font-semibold transition hover:border-primary/60 hover:text-primary">Kembali ke Pengembalian Aktif</a>
        </div>

        <form action="{{ route('admin.returns.riwayat') }}" method="GET" class="mb-5 grid gap-3 rounded-xl border border-border bg-card p-4 sm:grid-cols-[1fr_1fr_auto_auto] sm:items-end">
            <label class="block text-sm">
                <span class="mb-1.5 block text-muted-foreground">Anggota</span>
                <select name="member_id" class="w-full rounded-lg border border-input bg-background/60 px-3 py-2.5 outline-none focus:border-primary focus:ring-2 focus:ring-primary/40">
                    <option value="">Semua anggota</option>
                    @foreach ($members as $member)
                        <option value="{{ $member->id }}" @selected((string) request('member_id') === (string) $member->id)>{{ $member->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="block text-sm">
                <span class="mb-1.5 block text-muted-foreground">Status</span>
                <select name="status" class="w-full rounded-lg border border-input bg-background/60 px-3 py-2.5 outline-none focus:border-primary focus:ring-2 focus:ring-primary/40">
                    <option value="">Semua status</option>
                    <option value="dipinjam" @selected(request('status') === 'dipinjam')>Dipinjam</option>
                    <option value="dikembalikan" @selected(request('status') === 'dikembalikan')>Dikembalikan</option>
                </select>
            </label>
            <button type="submit" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground transition hover:brightness-110">Terapkan Filter</button>
            <a href="{{ route('admin.returns.riwayat') }}" class="rounded-lg border border-border px-4 py-2.5 text-center text-sm font-semibold transition hover:bg-muted">Reset</a>
        </form>

        <section class="overflow-hidden rounded-xl border border-border bg-card">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-border px-5 py-4">
                <div>
                    <h2 class="font-semibold">Semua transaksi</h2>
                    <p class="mt-1 text-xs text-muted-foreground">Pilih cetak untuk melihat laporan lengkap milik anggota.</p>
                </div>
                <span class="rounded-full bg-primary/15 px-3 py-1 text-xs font-semibold text-primary">{{ $riwayat->total() }} transaksi</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1000px] text-left text-sm">
                    <thead class="border-b border-border bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground">
                        <tr>
                            <th class="px-5 py-3">Anggota</th>
                            <th class="px-5 py-3">Unit</th>
                            <th class="px-5 py-3">Tanggal Pinjam</th>
                            <th class="px-5 py-3">Jatuh Tempo</th>
                            <th class="px-5 py-3">Tanggal Kembali</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Hari Terlambat</th>
                            <th class="px-5 py-3">Denda</th>
                            <th class="px-5 py-3">Admin</th>
                            <th class="px-5 py-3">Laporan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayat as $pinjaman)
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
                                <td class="whitespace-nowrap px-5 py-4 text-muted-foreground">{{ $pinjaman->tanggal_kembali?->format('d M Y') ?? '—' }}</td>
                                <td class="px-5 py-4">
                                    @if ($pinjaman->status === 'dikembalikan')
                                        <span class="rounded-full bg-success/15 px-2.5 py-1 text-xs font-semibold text-success">Dikembalikan</span>
                                    @else
                                        <span class="rounded-full bg-primary/15 px-2.5 py-1 text-xs font-semibold text-primary">Dipinjam</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">{{ number_format((int) $pinjaman->hari_terlambat, 0, ',', '.') }}</td>
                                <td class="whitespace-nowrap px-5 py-4">Rp{{ number_format((int) $pinjaman->denda, 0, ',', '.') }}</td>
                                <td class="px-5 py-4 text-muted-foreground">{{ $pinjaman->admin?->name ?? '—' }}</td>
                                <td class="px-5 py-4">
                                    @if ($pinjaman->profile?->user)
                                        <a href="{{ route('admin.returns.print', $pinjaman->profile->user) }}" class="whitespace-nowrap rounded-lg border border-primary/40 px-3 py-2 text-xs font-semibold text-primary transition hover:bg-primary/10">Cetak Anggota</a>
                                    @else
                                        <span class="text-xs text-muted-foreground">Tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-5 py-12 text-center">
                                    <p class="font-medium">Tidak ada riwayat yang cocok</p>
                                    <p class="mt-1 text-sm text-muted-foreground">Coba ubah filter anggota atau status.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
        <div class="mt-4">{{ $riwayat->links() }}</div>
    </main>
</div>
@endsection
