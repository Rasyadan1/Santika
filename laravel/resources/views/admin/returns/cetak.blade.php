@extends('layouts.app')

@section('content')
<style>
    @media print {
        body, body * { color: #111 !important; background: #fff !important; box-shadow: none !important; }
        .no-print { display: none !important; }
        .print-sheet { max-width: none !important; margin: 0 !important; padding: 0 !important; border: 0 !important; }
        table { border-collapse: collapse !important; }
        th, td { border: 1px solid #999 !important; }
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        @page { margin: 16mm; }
    }
</style>

<div class="flex min-h-screen flex-col bg-mystic md:flex-row">
    @include('partials.admin-navigation', ['hideWhenPrinting' => true])
    <main class="min-w-0 flex-1 px-4 py-8 md:px-8">
        <div class="print-sheet mx-auto max-w-5xl rounded-2xl border border-border bg-card p-5 sm:p-8">
        <div class="no-print mb-6 flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('admin.returns.riwayat') }}" class="rounded-lg border border-border px-4 py-2.5 text-sm font-semibold transition hover:bg-muted">← Kembali ke Riwayat</a>
            <button type="button" onclick="window.print()" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground transition hover:brightness-110">🖨️ Cetak Laporan</button>
        </div>

        <header class="border-b border-border pb-5">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="font-display text-3xl tracking-wider text-primary">SANTIKA</p>
                    <h1 class="mt-2 text-2xl font-bold">Laporan Riwayat Peminjaman</h1>
                    <p class="mt-1 text-sm text-muted-foreground">Ringkasan transaksi peminjaman dan pengembalian anggota.</p>
                </div>
                <div class="text-right text-sm text-muted-foreground">
                    <p>Tanggal cetak</p>
                    <p class="mt-1 font-semibold text-foreground">{{ now()->format('d M Y') }}</p>
                </div>
            </div>
        </header>

        <section class="mt-6 grid gap-4 sm:grid-cols-2">
            <div class="rounded-xl border border-border bg-background/40 p-4">
                <h2 class="mb-3 text-xs font-semibold uppercase tracking-widest text-primary">Data Anggota</h2>
                <dl class="grid grid-cols-[130px_1fr] gap-x-3 gap-y-2 text-sm">
                    <dt class="text-muted-foreground">Nama</dt><dd class="font-semibold">{{ $member->name }}</dd>
                    <dt class="text-muted-foreground">Nomor anggota</dt><dd>{{ $profile->nomor_anggota ?: '—' }}</dd>
                    <dt class="text-muted-foreground">Email</dt><dd class="break-all">{{ $member->email }}</dd>
                    <dt class="text-muted-foreground">No. telepon</dt><dd>{{ $profile->no_telepon ?: '—' }}</dd>
                    <dt class="text-muted-foreground">Alamat</dt><dd>{{ $profile->alamat ?: '—' }}</dd>
                </dl>
            </div>
            <div class="grid gap-3 sm:grid-cols-3 sm:content-start">
                <div class="rounded-xl border border-border bg-background/40 p-4">
                    <p class="text-xs text-muted-foreground">Total pinjaman</p>
                    <p class="mt-1 text-2xl font-bold">{{ number_format($totalPinjaman, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-xl border border-border bg-background/40 p-4">
                    <p class="text-xs text-muted-foreground">Pernah terlambat</p>
                    <p class="mt-1 text-2xl font-bold">{{ number_format($totalTerlambat, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-xl border border-border bg-background/40 p-4">
                    <p class="text-xs text-muted-foreground">Akumulasi denda</p>
                    <p class="mt-1 text-lg font-bold">Rp{{ number_format($totalDenda, 0, ',', '.') }}</p>
                </div>
            </div>
        </section>

        <section class="mt-7">
            <div class="mb-3 flex flex-wrap items-end justify-between gap-2">
                <div>
                    <h2 class="text-lg font-semibold">Rincian Peminjaman</h2>
                    <p class="text-xs text-muted-foreground">{{ $totalPinjaman }} transaksi tercatat.</p>
                </div>
            </div>
            <div class="overflow-x-auto rounded-xl border border-border">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground">
                        <tr>
                            <th class="px-4 py-3">Unit</th>
                            <th class="px-4 py-3">Tanggal Pinjam</th>
                            <th class="px-4 py-3">Jatuh Tempo</th>
                            <th class="px-4 py-3">Tanggal Kembali</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Terlambat</th>
                            <th class="px-4 py-3">Denda</th>
                            <th class="px-4 py-3">Diproses oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayat as $pinjaman)
                            <tr class="border-t border-border">
                                <td class="px-4 py-3">
                                    <p class="font-medium">{{ $pinjaman->unit?->nama_unit ?? 'Unit tidak ditemukan' }}</p>
                                    <p class="mt-1 font-mono text-xs text-muted-foreground">{{ $pinjaman->unit?->kode_unit ?? '—' }}</p>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3">{{ $pinjaman->tanggal_pinjam?->format('d M Y') ?? '—' }}</td>
                                <td class="whitespace-nowrap px-4 py-3">{{ $pinjaman->tanggal_jatuh_tempo?->format('d M Y') ?? '—' }}</td>
                                <td class="whitespace-nowrap px-4 py-3">{{ $pinjaman->tanggal_kembali?->format('d M Y') ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $pinjaman->status === 'dikembalikan' ? 'Dikembalikan' : 'Dipinjam' }}</td>
                                <td class="px-4 py-3">{{ number_format((int) $pinjaman->hari_terlambat, 0, ',', '.') }} hari</td>
                                <td class="whitespace-nowrap px-4 py-3">Rp{{ number_format((int) $pinjaman->denda, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">{{ $pinjaman->admin?->name ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-10 text-center text-muted-foreground">Belum ada riwayat peminjaman untuk anggota ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($riwayat->isNotEmpty())
                        <tfoot class="border-t-2 border-border bg-muted/30 font-semibold">
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-right">Total denda</td>
                                <td class="whitespace-nowrap px-4 py-3">Rp{{ number_format($totalDenda, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </section>

        <footer class="mt-8 border-t border-border pt-4 text-xs text-muted-foreground">
            Laporan ini dibuat oleh sistem Santika pada {{ now()->format('d M Y H:i') }}.
        </footer>
        </div>
    </main>
</div>
@endsection
