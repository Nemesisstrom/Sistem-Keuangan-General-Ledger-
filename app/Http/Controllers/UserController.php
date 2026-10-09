<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['branch', 'roles'])->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $branches = Branch::all();
        $roles = Role::all();
        return view('admin.users.create', compact('branches', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8',
            'branch_id' => 'nullable|exists:branches,id',
            'role'      => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'branch_id' => $validated['branch_id'],
            'is_active' => true,
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $branches = Branch::all();
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'branches', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password'  => 'nullable|string|min:8',
            'branch_id' => 'nullable|exists:branches,id',
            'role'      => 'required|exists:roles,name',
            'is_active' => 'required|boolean',
        ]);

        $userData = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'branch_id' => $validated['branch_id'],
            'is_active' => $validated['is_active'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
{
    // 1. Cegah pengguna menghapus akunnya sendiri
    if ($user->id === auth()->id) {
        return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
    }

    // 2. (Opsional) Cegah penghapusan user yang sudah memiliki riwayat entri jurnal
    if ($user->createdJournals()->exists()) {
        return back()->with('error', 'User tidak dapat dihapus karena memiliki riwayat entri jurnal.');
    }

    // 3. Proses hapus user
    $user->delete();

    return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
}
}
