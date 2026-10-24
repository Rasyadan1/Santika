@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-mystic pb-12">
    @include('partials.member-navigation')

    <main class="mx-auto max-w-7xl px-4 py-10">
        <header class="mb-8">
            <p class="text-sm text-muted-foreground">Catatan peminjaman akun Anda</p>
            <h1 class="mt-1 font-display text-4xl tracking-wide text-primary">Riwayat Pinjaman</h1>
            <p class="mt-2 text-muted-foreground">Daftar peminjaman yang sedang berjalan maupun yang telah selesai.</p>
        </header>

        <div class="overflow-hidden rounded-2xl border border-border bg-glass backdrop-blur-xl">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="border-b border-border bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground">
                        <tr>
                            <th class="px-5 py-4">Unit</th>
                            <th class="px-5 py-4 whitespace-nowrap">Tanggal Pinjam</th>
                            <th class="px-5 py-4 whitespace-nowrap">Jatuh Tempo</th>
                            <th class="px-5 py-4 whitespace-nowrap">Tanggal Kembali</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4 text-right">Denda</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse ($riwayat as $pinjaman)
                            @php($terlambat = $pinjaman->isTerlambat())
                            <tr class="transition hover:bg-muted/30">
                                <td class="px-5 py-4">
                                    <p class="font-medium">{{ $pinjaman->unit?->nama_unit ?? 'Unit tidak tersedia' }}</p>
                                    @if ($pinjaman->unit?->kode_unit)
                                        <p class="mt-1 font-mono text-xs text-muted-foreground">{{ $pinjaman->unit->kode_unit }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-muted-foreground">{{ $pinjaman->tanggal_pinjam?->format('d M Y') ?? '—' }}</td>
                                <td class="px-5 py-4 whitespace-nowrap {{ $terlambat ? 'text-destructive' : 'text-muted-foreground' }}">{{ $pinjaman->tanggal_jatuh_tempo?->format('d M Y') ?? '—' }}</td>
                                <td class="px-5 py-4 whitespace-nowrap text-muted-foreground">{{ $pinjaman->tanggal_kembali?->format('d M Y') ?? '—' }}</td>
                                <td class="px-5 py-4">
                                    @if ($pinjaman->status === 'dikembalikan')
                                        <span class="rounded-full bg-success/15 px-3 py-1 text-xs font-semibold text-success">Dikembalikan</span>
                                    @elseif ($terlambat)
                                        <span class="rounded-full bg-destructive/15 px-3 py-1 text-xs font-semibold text-destructive">Terlambat</span>
                                    @else
                                        <span class="rounded-full bg-primary/15 px-3 py-1 text-xs font-semibold text-primary">Dipinjam</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right whitespace-nowrap {{ $pinjaman->denda > 0 ? 'font-semibold text-destructive' : 'text-muted-foreground' }}">Rp {{ number_format($pinjaman->denda, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <span class="text-4xl">📜</span>
                                    <p class="mt-3 font-medium">Belum ada riwayat pinjaman</p>
                                    <p class="mt-1 text-sm text-muted-foreground">Peminjaman Anda akan tercatat di sini.</p>
                                    <a href="{{ route('katalog') }}" class="mt-4 inline-flex text-sm font-semibold text-primary hover:underline">Lihat katalog</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($riwayat->hasPages())
            <div class="mt-5">{{ $riwayat->links() }}</div>
        @endif

        <div class="mt-6 flex flex-wrap gap-4">
            <a href="{{ route('pinjaman.index') }}" class="text-sm font-medium text-primary transition hover:underline">← Pinjaman aktif</a>
            <a href="{{ route('katalog') }}" class="text-sm font-medium text-primary transition hover:underline">Kembali ke katalog</a>
        </div>
    </main>
</div>
@endsection
