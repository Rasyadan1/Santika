@extends('layouts.app')

@section('content')
<div class="bg-mystic min-h-screen flex items-center justify-center px-4 py-10">

    <div class="bg-glass glow-primary backdrop-blur-xl border border-border rounded-3xl w-full max-w-lg p-8 sm:p-10">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="text-6xl animate-float inline-block mb-4">👻</div>
            <h1 class="font-display text-4xl sm:text-5xl text-primary tracking-wide">Daftar Anggota Gaib</h1>
            <p class="text-foreground/60 mt-3 text-sm">Bergabunglah dengan arwah-arwah pilihan malam ini.</p>
        </div>

        <!-- Validasi Error -->
        @if ($errors->any())
            <div class="mb-5 rounded-lg border border-destructive/50 bg-destructive/20 p-4">
                <ul class="list-disc pl-5 text-sm text-destructive-foreground">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Register -->
        <form class="space-y-5" action="/register" method="POST">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label for="nama" class="block text-sm font-medium text-foreground/80 mb-2">Nama Lengkap</label>
                <input id="nama" name="name" type="text" value="{{ old('name') }}" placeholder="Masukkan nama lengkap Anda" required class="w-full bg-input border border-border rounded-xl px-4 py-3 text-foreground placeholder-foreground/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-foreground/80 mb-2">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@contoh.com" required class="w-full bg-input border border-border rounded-xl px-4 py-3 text-foreground placeholder-foreground/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all">
            </div>

            <!-- No. Telepon -->
            <div>
                <label for="no_telepon" class="block text-sm font-medium text-foreground/80 mb-2">No. Telepon</label>
                <input id="no_telepon" name="no_telepon" type="tel" value="{{ old('no_telepon') }}" placeholder="08xxxxxxxxxx" required class="w-full bg-input border border-border rounded-xl px-4 py-3 text-foreground placeholder-foreground/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-foreground/80 mb-2">Password</label>
                <input id="password" name="password" type="password" placeholder="••••••••" required class="w-full bg-input border border-border rounded-xl px-4 py-3 text-foreground placeholder-foreground/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all">
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-foreground/80 mb-2">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="••••••••" required class="w-full bg-input border border-border rounded-xl px-4 py-3 text-foreground placeholder-foreground/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all">
            </div>

            <!-- Alamat Lengkap -->
            <div>
                <label for="alamat" class="block text-sm font-medium text-foreground/80 mb-2">Alamat Lengkap</label>
                <textarea id="alamat" name="alamat" rows="3" placeholder="Jl. Kuburan No. 13, RT/RW, Kelurahan, Kota" required class="w-full bg-input border border-border rounded-xl px-4 py-3 text-foreground placeholder-foreground/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/30 transition-all resize-none">{{ old('alamat') }}</textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-semibold text-base rounded-xl py-3.5 mt-2 transition-all hover:glow-primary active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-primary/40">
                Resmi Jadi Anggota
            </button>

        </form>

        <!-- Footer link -->
        <p class="text-center text-sm text-foreground/50 mt-6">
            Sudah jadi anggota?
            <a href="/" class="text-primary hover:text-primary/80 font-medium transition-colors">Masuk di sini</a>
        </p>

    </div>

</div>
@endsection