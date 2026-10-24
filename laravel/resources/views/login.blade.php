@extends('layouts.app')

@section('content')
<main class="flex min-h-screen items-center justify-center bg-mystic px-4 py-16">
    <div class="w-full max-w-md rounded-2xl border border-primary/40 bg-glass p-8 glow-primary backdrop-blur-xl">
        <div class="mb-8 text-center">
            <div class="mb-2 inline-block animate-float text-5xl">👻</div>
            <h1 class="font-display text-5xl tracking-wider text-primary">SANTIKA</h1>
            <p class="mt-1 text-sm text-muted-foreground">Sewa Alat Santet dan Pelet Seketika</p>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-success/50 bg-success/10 p-3 text-sm text-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-destructive/50 bg-destructive/20 p-3 text-sm text-destructive-foreground">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="space-y-5" action="{{ route('login.store') }}" method="POST">
            @csrf

            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="dukun@santika.id" autocomplete="username" required autofocus class="w-full rounded-lg border border-input bg-background/60 px-4 py-2.5 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40" />
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-sm font-medium">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" placeholder="Mantra rahasia" autocomplete="current-password" required class="w-full rounded-lg border border-input bg-background/60 px-4 py-2.5 pr-12 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40" />
                    <button id="toggle-password" type="button" aria-label="Tampilkan password" aria-pressed="false" aria-controls="password" class="absolute inset-y-0 right-0 flex items-center rounded-r-lg px-3 text-muted-foreground transition hover:text-primary focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary/50">
                        <svg id="password-eye" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.4-6.5 9.5-6.5 9.5 6.5 9.5 6.5-3.4 6.5-9.5 6.5S2.5 12 2.5 12Z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        <svg id="password-eye-off" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" class="hidden h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.1A10.8 10.8 0 0 1 12 4.8c6.2 0 9.5 7.2 9.5 7.2a16.4 16.4 0 0 1-3.1 4.1M6.2 6.2C3.8 7.8 2.5 12 2.5 12s3.3 7.2 9.5 7.2a10 10 0 0 0 4.1-.9" />
                        </svg>
                    </button>
                </div>
            </div>

            <label for="remember" class="flex items-center gap-2 text-sm text-muted-foreground">
                <input id="remember" type="checkbox" name="remember" value="1" class="rounded border-input bg-background text-primary focus:ring-primary" />
                Ingat saya
            </label>

            <button type="submit" class="w-full rounded-lg bg-primary py-3 font-semibold text-primary-foreground glow-primary transition hover:brightness-110">
                Masuk ke Alam Gaib
            </button>
        </form>

        <p class="mt-6 text-center text-xs text-muted-foreground">Akun anggota dibuat oleh Admin Santika.</p>
    </div>
</main>

<script>
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.getElementById('toggle-password');
    const passwordEye = document.getElementById('password-eye');
    const passwordEyeOff = document.getElementById('password-eye-off');

    passwordToggle.addEventListener('click', () => {
        const isVisible = passwordInput.type === 'password';

        passwordInput.type = isVisible ? 'text' : 'password';
        passwordToggle.setAttribute('aria-label', isVisible ? 'Sembunyikan password' : 'Tampilkan password');
        passwordToggle.setAttribute('aria-pressed', String(isVisible));
        passwordEye.classList.toggle('hidden', isVisible);
        passwordEyeOff.classList.toggle('hidden', !isVisible);
    });
</script>
@endsection
