@if ($errors->any())
    <div class="rounded-lg border border-destructive/50 bg-destructive/10 p-4 text-sm text-destructive" role="alert">
        <p class="mb-2 font-semibold">Periksa kembali isian berikut:</p>
        <ul class="list-disc space-y-1 pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label for="name" class="mb-1.5 block text-sm font-medium">Nama Lengkap <span class="text-destructive">*</span></label>
        <input id="name" name="name" type="text" value="{{ old('name', $member->name ?? '') }}" maxlength="255" required autocomplete="name" placeholder="Nama anggota" class="w-full rounded-lg border border-input bg-background/60 px-4 py-2.5 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40">
        @error('name')<p class="mt-1 text-xs text-destructive">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="email" class="mb-1.5 block text-sm font-medium">Email <span class="text-destructive">*</span></label>
        <input id="email" name="email" type="email" value="{{ old('email', $member->email ?? '') }}" maxlength="255" required autocomplete="email" placeholder="anggota@santika.id" class="w-full rounded-lg border border-input bg-background/60 px-4 py-2.5 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40">
        @error('email')<p class="mt-1 text-xs text-destructive">{{ $message }}</p>@enderror
    </div>
</div>

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label for="no_telepon" class="mb-1.5 block text-sm font-medium">Nomor Telepon</label>
        <input id="no_telepon" name="no_telepon" type="tel" value="{{ old('no_telepon', $member->profile->no_telepon ?? '') }}" maxlength="20" autocomplete="tel" placeholder="Contoh: 081234567890" class="w-full rounded-lg border border-input bg-background/60 px-4 py-2.5 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40">
        @error('no_telepon')<p class="mt-1 text-xs text-destructive">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="alamat" class="mb-1.5 block text-sm font-medium">Alamat</label>
        <textarea id="alamat" name="alamat" rows="2" maxlength="500" autocomplete="street-address" placeholder="Alamat anggota" class="w-full resize-y rounded-lg border border-input bg-background/60 px-4 py-2.5 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40">{{ old('alamat', $member->profile->alamat ?? '') }}</textarea>
        @error('alamat')<p class="mt-1 text-xs text-destructive">{{ $message }}</p>@enderror
    </div>
</div>

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label for="password" class="mb-1.5 block text-sm font-medium">Password @unless(isset($member))<span class="text-destructive">*</span>@endunless</label>
        <input id="password" name="password" type="password" @unless(isset($member)) required @endunless minlength="8" autocomplete="new-password" placeholder="Minimal 8 karakter{{ isset($member) ? ' — kosongkan jika tidak diubah' : '' }}" class="w-full rounded-lg border border-input bg-background/60 px-4 py-2.5 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40">
        @error('password')<p class="mt-1 text-xs text-destructive">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="password_confirmation" class="mb-1.5 block text-sm font-medium">Konfirmasi Password @unless(isset($member))<span class="text-destructive">*</span>@endunless</label>
        <input id="password_confirmation" name="password_confirmation" type="password" @unless(isset($member)) required @endunless minlength="8" autocomplete="new-password" placeholder="Ulangi password" class="w-full rounded-lg border border-input bg-background/60 px-4 py-2.5 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40">
    </div>
</div>
@if (isset($member))<p class="-mt-3 text-xs text-muted-foreground">Biarkan kedua kolom password kosong untuk mempertahankan password saat ini.</p>@else<p class="-mt-3 text-xs text-muted-foreground">Password minimal 8 karakter. Akun anggota akan dibuat dengan peran anggota.</p>@endif

<div class="flex flex-col-reverse justify-end gap-3 border-t border-border pt-5 sm:flex-row">
    <a href="{{ route('admin.members.index') }}" class="rounded-lg border border-border px-5 py-2.5 text-center text-sm font-medium text-muted-foreground transition hover:bg-muted hover:text-foreground">Batal</a>
    <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground transition hover:brightness-110">{{ $submitLabel }}</button>
</div>
