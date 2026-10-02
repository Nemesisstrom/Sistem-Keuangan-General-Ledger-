<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Tampilkan semua cabang.
     */
    public function index()
    {
        $branches = Branch::withCount(['users', 'employees'])->get();
        return response()->json($branches);
    }

    /**
     * Tambah cabang baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'    => 'required|string|max:10|unique:branches,code',
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone'   => 'nullable|string|max:20',
        ]);

        $branch = Branch::create($validated);

        return response()->json(['message' => 'Cabang berhasil ditambahkan.', 'data' => $branch], 201);
    }

    /**
     * Detail cabang.
     */
    public function show(Branch $branch)
    {
        return response()->json($branch->load(['users', 'employees']));
    }

    /**
     * Update data cabang.
     */
    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'code'      => 'required|string|max:10|unique:branches,code,' . $branch->id,
            'name'      => 'required|string|max:255',
            'address'   => 'nullable|string',
            'phone'     => 'nullable|string|max:20',
            'is_active' => 'required|boolean',
        ]);

        $branch->update($validated);

        return response()->json(['message' => 'Data cabang berhasil diperbarui.', 'data' => $branch]);
    }

    /**
     * Hapus cabang.
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();

        return response()->json(['message' => 'Cabang berhasil dihapus.']);
    }
}
