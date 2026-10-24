@extends('layouts.app')

@section('content')
<div class="flex min-h-screen flex-col bg-mystic md:flex-row">
    @include('partials.admin-navigation')
    <main class="min-w-0 flex-1 p-4 pb-12 md:p-8">
        <div class="mx-auto max-w-5xl">
        <div class="mb-6"><p class="text-sm text-primary">Pengaturan Inventaris</p><h1 class="text-2xl font-bold sm:text-3xl">Tambah Kategori</h1><p class="mt-1 text-sm text-muted-foreground">Buat kategori untuk mengelompokkan unit.</p></div>
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6 rounded-2xl border border-primary/30 bg-card/90 p-5 shadow-xl backdrop-blur-xl sm:p-8">
            @csrf
            @include('admin.categories._form', ['submitLabel' => 'Simpan Kategori'])
        </form>
        </div>
    </main>
</div>
@endsection
