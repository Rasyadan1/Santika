@extends('layouts.app')

@section('content')
<main class="flex min-h-screen items-center justify-center bg-mystic px-4 py-16">
    <div class="w-full max-w-md rounded-2xl border border-primary/40 bg-glass p-8 glow-primary backdrop-blur-xl">
        <!-- Logo & Title -->
        <div class="mb-8 text-center">
            <div class="mb-2 text-5xl animate-float inline-block">👻</div>
            <h1 class="font-display text-5xl tracking-wider text-primary">SANTIKA</h1>
            <p class="mt-1 text-sm text-muted-foreground">Sewa Alat Santet dan Pelet Seketika</p>
        </div>

        <!-- Notifikasi Error Login -->
        @if(session('error'))
            <div class="mb-4 rounded border border-destructive/50 bg-destructive/20 p-3 text-sm text-destructive-foreground">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form Login -->
        <form class="space-y-5" action="/login" method="POST">
            @csrf
            
            <!-- Input Email -->
            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="dukun@santika.id" required class="w-full rounded-lg border border-input bg-background/60 px-4 py-2.5 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40" />
                @error('email')
                    <span class="mt-1 block text-xs text-destructive">{{ $message }}</span>
                @enderror
            </div>

            <!-- Input Password -->
            <div>
                <label for="password" class="mb-1.5 block text-sm font-medium">Password</label>
                <input id="password" type="password" name="password" placeholder="Mantra rahasia" required class="w-full rounded-lg border border-input bg-background/60 px-4 py-2.5 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40" />
                @error('password')
                    <span class="mt-1 block text-xs text-destructive">{{ $message }}</span>
                @enderror
            </div>

            <!-- Action Buttons -->
            <button type="submit" class="w-full rounded-lg bg-primary py-3 font-semibold text-primary-foreground glow-primary transition hover:brightness-110">
                Masuk ke Alam Gaib
            </button>
            <a href="/register" class="block w-full rounded-lg border border-destructive/60 py-3 text-center font-semibold text-destructive transition hover:bg-destructive hover:text-destructive-foreground">
                Daftar Anggota
            </a>
        </form>

        <p class="mt-6 text-center text-xs text-muted-foreground">Dijamin ampuh atau arwah kembali.</p>
    </div>
</main>
@endsection