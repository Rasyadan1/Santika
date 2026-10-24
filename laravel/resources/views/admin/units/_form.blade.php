@if ($errors->any())
    <div class="rounded-lg border border-destructive/50 bg-destructive/10 p-4 text-sm text-destructive" role="alert">
        <p class="mb-2 font-semibold">Periksa kembali isian berikut:</p>
        <ul class="list-disc space-y-1 pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label for="kode_unit" class="mb-1.5 block text-sm font-medium">Kode Unit <span class="text-destructive">*</span></label>
        <input id="kode_unit" name="kode_unit" type="text" value="{{ old('kode_unit', $unit->kode_unit ?? '') }}" maxlength="50" required autocomplete="off" placeholder="Contoh: ST-001" class="w-full rounded-lg border border-input bg-background/60 px-4 py-2.5 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40">
        @error('kode_unit')<p class="mt-1 text-xs text-destructive">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="nama_unit" class="mb-1.5 block text-sm font-medium">Nama Unit <span class="text-destructive">*</span></label>
        <input id="nama_unit" name="nama_unit" type="text" value="{{ old('nama_unit', $unit->nama_unit ?? '') }}" maxlength="255" required placeholder="Nama perlengkapan mistis" class="w-full rounded-lg border border-input bg-background/60 px-4 py-2.5 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40">
        @error('nama_unit')<p class="mt-1 text-xs text-destructive">{{ $message }}</p>@enderror
    </div>
</div>

<div>
    <label for="tarif_per_hari" class="mb-1.5 block text-sm font-medium">Tarif per Hari (Rp) <span class="text-destructive">*</span></label>
    <input id="tarif_per_hari" name="tarif_per_hari" type="number" min="0" step="1" value="{{ old('tarif_per_hari', $unit->tarif_per_hari ?? '') }}" required inputmode="numeric" placeholder="Contoh: 50000" class="w-full rounded-lg border border-input bg-background/60 px-4 py-2.5 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40">
    @error('tarif_per_hari')<p class="mt-1 text-xs text-destructive">{{ $message }}</p>@enderror
</div>

<fieldset>
    <legend class="mb-2 block text-sm font-medium">Kategori <span class="text-destructive">*</span></legend>
    <p class="mb-3 text-xs text-muted-foreground">Pilih setidaknya satu kategori untuk unit ini.</p>
    <div class="grid gap-2 sm:grid-cols-2">
        @forelse ($categories as $category)
            <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-border bg-background/40 px-4 py-3 transition hover:border-primary/50 has-[:checked]:border-primary has-[:checked]:bg-primary/10">
                <input type="checkbox" name="categories[]" value="{{ $category->id }}" @checked(in_array($category->id, old('categories', isset($unit) ? $unit->categories->modelKeys() : []))) class="h-4 w-4 accent-primary">
                <span class="text-sm">{{ $category->nama_kategori }}</span>
            </label>
        @empty
            <p class="rounded-lg border border-destructive/40 bg-destructive/10 p-3 text-sm text-destructive">Belum ada kategori. Buat kategori terlebih dahulu.</p>
        @endforelse
    </div>
    @error('categories')<p class="mt-2 text-xs text-destructive">{{ $message }}</p>@enderror
    @error('categories.*')<p class="mt-2 text-xs text-destructive">{{ $message }}</p>@enderror
</fieldset>

<div>
    <label for="deskripsi" class="mb-1.5 block text-sm font-medium">Deskripsi</label>
    <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="1000" placeholder="Jelaskan detail unit..." class="w-full resize-y rounded-lg border border-input bg-background/60 px-4 py-3 text-sm outline-none placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/40">{{ old('deskripsi', $unit->deskripsi ?? '') }}</textarea>
    @error('deskripsi')<p class="mt-1 text-xs text-destructive">{{ $message }}</p>@enderror
</div>

<div class="flex flex-col-reverse justify-end gap-3 border-t border-border pt-5 sm:flex-row">
    <a href="{{ route('admin.units.index') }}" class="rounded-lg border border-border px-5 py-2.5 text-center text-sm font-medium text-muted-foreground transition hover:bg-muted hover:text-foreground">Batal</a>
    <button type="submit" class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-primary-foreground transition hover:brightness-110">{{ $submitLabel }}</button>
</div>
