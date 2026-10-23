<?php

namespace App\Http\Controllers;

use App\Http\Requests\UnitRequest;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Katalog unit (untuk anggota) — dengan pencarian.
     */
    public function katalog(Request $request)
    {
        $search = $request->input('search');

        $units = Unit::with('categories')
            ->when($search, function ($query) use ($search) {
                $query->where('nama_unit', 'like', "%{$search}%");
            })
            ->orderBy('nama_unit')
            ->paginate(12)
            ->appends(['search' => $search]);

        return view('units.katalog', compact('units', 'search'));
    }

    /**
     * Admin: Daftar semua unit.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $units = Unit::with('categories')
            ->when($search, function ($query) use ($search) {
                $query->where('nama_unit', 'like', "%{$search}%")
                    ->orWhere('kode_unit', 'like', "%{$search}%");
            })
            ->orderBy('kode_unit')
            ->paginate(15)
            ->appends(['search' => $search]);

        return view('admin.units.index', compact('units', 'search'));
    }

    /**
     * Admin: Form tambah unit.
     */
    public function create()
    {
        $categories = Category::orderBy('nama_kategori')->get();
        return view('admin.units.create', compact('categories'));
    }

    /**
     * Admin: Simpan unit baru.
     */
    public function store(UnitRequest $request)
    {
        $unit = Unit::create($request->only([
            'kode_unit', 'nama_unit', 'deskripsi', 'tarif_per_hari'
        ]));

        $unit->categories()->sync($request->categories);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit berhasil ditambahkan.');
    }

    /**
     * Admin: Form edit unit.
     */
    public function edit(Unit $unit)
    {
        $categories = Category::orderBy('nama_kategori')->get();
        $unit->load('categories');

        return view('admin.units.edit', compact('unit', 'categories'));
    }

    /**
     * Admin: Update unit.
     */
    public function update(UnitRequest $request, Unit $unit)
    {
        $unit->update($request->only([
            'kode_unit', 'nama_unit', 'deskripsi', 'tarif_per_hari'
        ]));

        $unit->categories()->sync($request->categories);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit berhasil diperbarui.');
    }

    /**
     * Admin: Hapus unit.
     */
    public function destroy(Unit $unit)
    {
        if ($unit->hasPinjamanAktif()) {
            return back()->with('error', 'Unit tidak dapat dihapus karena masih memiliki pinjaman aktif.');
        }

        try {
            $unit->delete();
            return redirect()->route('admin.units.index')
                ->with('success', 'Unit berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Unit tidak dapat dihapus karena masih memiliki riwayat peminjaman.');
        }
    }
}
