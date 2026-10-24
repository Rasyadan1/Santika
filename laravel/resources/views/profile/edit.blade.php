@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-mystic pb-12">
    @include('partials.member-navigation')

    <main class="mx-auto max-w-3xl px-4 py-10">
        <header class="mb-8">
            <p class="text-sm text-muted-foreground">Pengaturan akun anggota</p>
            <h1 class="mt-1 font-display text-4xl tracking-wide text-primary">Ubah Profil</h1>
            <p class="mt-2 text-muted-foreground">Perbarui informasi pribadi Anda. Kosongkan bagian password jika tidak ingin menggantinya.</p>
        </header>

        @if ($errors->any())
            <div class="mb-5 rounded-lg border border-destructive/50 bg-destructive/10 p-4 text-sm text-destructive" role="alert">
                <p class="mb-2 font-semibold">Periksa kembali data yang dimasukkan:</p>
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6 rounded-2xl border border-border bg-glass p-6 backdrop-blur-xl sm:p-8">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="mb-2 block text-sm font-medium">Nama Lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required maxlength="255" autocomplete="name" class="w-full rounded-lg border border-input bg-background/60 px-4 py-3 text-foreground outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/40">
                @error('name')<p class="mt-1 text-sm text-destructive">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium">Email</label>
                <input id="email" type="email" value="{{ $user->email }}" disabled class="w-full cursor-not-allowed rounded-lg border border-input bg-muted px-4 py-3 text-muted-foreground">
                <p class="mt-1 text-xs text-muted-foreground">Email tidak dapat diubah dari halaman ini.</p>
            </div>

            <div>
                <label for="no_telepon" class="mb-2 block text-sm font-medium">No. Telepon</label>
                <input id="no_telepon" name="no_telepon" type="tel" value="{{ old('no_telepon', $profile?->no_telepon) }}" maxlength="20" autocomplete="tel" class="w-full rounded-lg border border-input bg-background/60 px-4 py-3 text-foreground outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/40">
                @error('no_telepon')<p class="mt-1 text-sm text-destructive">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="alamat" class="mb-2 block text-sm font-medium">Alamat</label>
                <textarea id="alamat" name="alamat" rows="4" maxlength="500" autocomplete="street-address" class="w-full resize-y rounded-lg border border-input bg-background/60 px-4 py-3 text-foreground outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/40">{{ old('alamat', $profile?->alamat) }}</textarea>
                @error('alamat')<p class="mt-1 text-sm text-destructive">{{ $message }}</p>@enderror
            </div>

            <div class="border-t border-border pt-5">
                <h2 class="font-semibold">Ganti Password <span class="font-normal text-muted-foreground">(opsional)</span></h2>
                <p class="mt-1 text-sm text-muted-foreground">Gunakan minimal 8 karakter dan masukkan konfirmasi yang sama.</p>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium">Password Baru</label>
                        <input id="password" name="password" type="password" minlength="8" autocomplete="new-password" class="w-full rounded-lg border border-input bg-background/60 px-4 py-3 text-foreground outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/40">
                        @error('password')<p class="mt-1 text-sm text-destructive">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-medium">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" autocomplete="new-password" class="w-full rounded-lg border border-input bg-background/60 px-4 py-3 text-foreground outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/40">
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-border pt-5 sm:flex-row sm:justify-between">
                <a href="{{ route('profile.show') }}" class="inline-flex items-center justify-center rounded-lg border border-border px-5 py-2.5 text-sm font-medium transition hover:bg-muted">Batal</a>
                <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground transition hover:brightness-110">Simpan Perubahan</button>
            </div>
        </form>

        <a href="{{ route('katalog') }}" class="mt-6 inline-flex text-sm font-medium text-primary transition hover:underline">← Kembali ke katalog</a>
    </main>
</div>
@endsection
