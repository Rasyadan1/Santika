@extends('layouts.app')

@section('content')
<div class="flex min-h-screen flex-col bg-mystic md:flex-row">
    @include('partials.admin-navigation')

    <main class="min-w-0 flex-1 p-4 pb-12 md:p-8">
        @if (session('success'))<div class="mb-4 rounded-lg border border-success/50 bg-success/10 p-3 text-sm text-success">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="mb-4 rounded-lg border border-destructive/50 bg-destructive/10 p-3 text-sm text-destructive">{{ session('error') }}</div>@endif
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div><p class="text-sm text-primary">Data Pengguna</p><h1 class="text-2xl font-bold">Anggota</h1><p class="text-sm text-muted-foreground">Kelola akun dan informasi kontak anggota.</p></div>
            <a href="{{ route('admin.members.create') }}" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground transition hover:brightness-110">＋ Tambah Anggota</a>
        </div>

        <form action="{{ route('admin.members.index') }}" method="GET" class="mt-6 flex w-full max-w-2xl gap-2">
            <label for="member-search" class="sr-only">Cari anggota</label>
            <input id="member-search" type="search" name="search" value="{{ $search }}" placeholder="Cari nama, email, nomor anggota, telepon, atau alamat..." class="min-w-0 flex-1 rounded-lg border border-input bg-background/60 px-4 py-2.5 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40">
            <button type="submit" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground transition hover:brightness-110">Cari</button>
            @if ($search)
                <a href="{{ route('admin.members.index') }}" class="rounded-lg border border-border px-4 py-2.5 text-sm text-muted-foreground transition hover:bg-muted">Reset</a>
            @endif
        </form>

        <div class="mt-5 overflow-x-auto rounded-xl border border-border bg-card">
            <table class="w-full min-w-[850px] text-left text-sm">
                <thead class="border-b border-border bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground"><tr><th class="px-5 py-3">Anggota</th><th class="px-5 py-3">Nomor Anggota</th><th class="px-5 py-3">Telepon</th><th class="px-5 py-3">Alamat</th><th class="px-5 py-3 text-right">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($members as $member)
                        @php($profile = $member->profile)
                        <tr class="border-b border-border last:border-0 transition hover:bg-muted/40">
                            <td class="px-5 py-4"><p class="font-medium">{{ $member->name }}</p><p class="mt-1 text-xs text-muted-foreground">{{ $member->email }}</p></td>
                            <td class="px-5 py-4 font-mono text-xs text-muted-foreground">{{ $profile->nomor_anggota ?? '—' }}</td>
                            <td class="px-5 py-4">{{ $profile->no_telepon ?? '—' }}</td>
                            <td class="max-w-xs px-5 py-4 text-muted-foreground"><span class="block truncate">{{ $profile->alamat ?? '—' }}</span></td>
                            <td class="px-5 py-4"><div class="flex justify-end gap-2"><a href="{{ route('admin.members.edit', $member) }}" class="rounded-lg border border-primary/50 px-3 py-1.5 text-xs font-medium text-primary transition hover:bg-primary/10">Edit</a><form action="{{ route('admin.members.destroy', $member) }}" method="POST" onsubmit="return confirm('Hapus anggota {{ addslashes($member->name) }}? Pastikan anggota tidak memiliki pinjaman aktif atau riwayat yang menghalangi penghapusan.');">@csrf @method('DELETE')<button type="submit" class="rounded-lg border border-destructive/50 px-3 py-1.5 text-xs font-medium text-destructive transition hover:bg-destructive/10">Hapus</button></form></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-muted-foreground">{{ $search ? 'Tidak ada anggota yang cocok dengan pencarian.' : 'Belum ada anggota terdaftar.' }}</td></tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $members->links() }}</div>
    </main>
</div>
@endsection
