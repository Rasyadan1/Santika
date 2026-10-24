@extends('layouts.app')

@section('content')
<div class="flex min-h-screen flex-col bg-mystic md:flex-row">
    @include('partials.admin-navigation')
    <main class="min-w-0 flex-1 p-4 pb-12 md:p-8">
        <div class="mx-auto max-w-5xl">
        <div class="mb-6"><p class="text-sm text-primary">Inventaris Santika</p><h1 class="text-2xl font-bold sm:text-3xl">Tambah Unit</h1><p class="mt-1 text-sm text-muted-foreground">Daftarkan perlengkapan baru beserta tarif dan kategorinya.</p></div>

        <form action="{{ route('admin.units.store') }}" method="POST" class="space-y-6 rounded-2xl border border-primary/30 bg-card/90 p-5 shadow-xl backdrop-blur-xl sm:p-8">
            @csrf
            @include('admin.units._form', ['submitLabel' => 'Simpan Unit'])
        </form>
        </div>
    </main>
</div>
@endsection
