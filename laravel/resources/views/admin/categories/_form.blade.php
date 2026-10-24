@if ($errors->any())
    <div class="rounded-lg border border-destructive/50 bg-destructive/10 p-4 text-sm text-destructive" role="alert">
        <p class="mb-2 font-semibold">Periksa kembali isian berikut:</p>
        <ul class="list-disc space-y-1 pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div>
    <label for="nama_kategori" class="mb-1.5 block text-sm font-medium">Nama Kategori <span class="text-destructive">*</span></label>
    <input id="nama_kategori" name="nama_kategori" type="text" value="{{ old('nama_kategori', $category->nama_kategori ?? '') }}" maxlength="255" required autofocus placeholder="Contoh: Pusaka" class="w-full rounded-lg border border-input bg-background/60 px-4 py-2.5 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40">
    @error('nama_kategori')<p class="mt-1 text-xs text-destructive">{{ $message }}</p>@enderror
</div>

<div>
    <label for="deskripsi" class="mb-1.5 block text-sm font-medium">Deskripsi</label>
    <textarea id="deskripsi" name="deskripsi" rows="5" maxlength="1000" placeholder="Jelaskan kategori ini..." class="w-full resize-y rounded-lg border border-input bg-background/60 px-4 py-3 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40">{{ old('deskripsi', $category->deskripsi ?? '') }}</textarea>
    @error('deskripsi')<p class="mt-1 text-xs text-destructive">{{ $message }}</p>@enderror
</div>

<div class="flex flex-col-reverse justify-end gap-3 border-t border-border pt-5 sm:flex-row">
    <a href="{{ route('admin.categories.index') }}" class="rounded-lg border border-border px-5 py-2.5 text-center text-sm font-medium text-muted-foreground transition hover:bg-muted hover:text-foreground">Batal</a>
    <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground transition hover:brightness-110">{{ $submitLabel }}</button>
</div>
