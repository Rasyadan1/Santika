@extends('layouts.app')

@section('content')
<div class="flex min-h-screen flex-col bg-mystic md:flex-row">
    @include('partials.admin-navigation')
    <main class="min-w-0 flex-1 p-4 pb-12 md:p-8">
        <div class="mx-auto max-w-5xl">
        <div class="mb-6"><p class="text-sm text-primary">Pengaturan Inventaris</p><h1 class="text-2xl font-bold sm:text-3xl">Edit Kategori</h1><p class="mt-1 text-sm text-muted-foreground">Perbarui informasi kategori <span class="font-medium text-foreground">{{ $category->nama_kategori }}</span>.</p></div>
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6 rounded-2xl border border-primary/30 bg-card/90 p-5 shadow-xl backdrop-blur-xl sm:p-8">
            @csrf
            @method('PUT')
            @include('admin.categories._form', ['submitLabel' => 'Simpan Perubahan'])
        </form>
        </div>
    </main>
</div>
@endsection
