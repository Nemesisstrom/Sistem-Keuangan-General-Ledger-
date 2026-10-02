<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Daftar Karyawan.
     */
    public function index(Request $request)
    {
        $employees = Employee::with('branch')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('nik', 'like', "%{$search}%");
            })
            ->when($request->has('is_active'), function ($query) use ($request) {
                $query->where('is_active', $request->boolean('is_active'));
            })
            ->paginate(15);

        return response()->json($employees);
    }

    /**
     * Tambah karyawan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id'           => 'nullable|exists:branches,id',
            'nik'                 => 'required|string|max:20|unique:employees,nik',
            'name'                => 'required|string|max:255',
            'email'               => 'nullable|email|max:255',
            'position'            => 'required|string|max:100',
            'basic_salary'        => 'required|numeric|min:0',
            'npwp'                => 'nullable|string|max:25',
            'ptkp_status'         => 'required|in:TK/0,TK/1,TK/2,TK/3,K/0,K/1,K/2,K/3',
            'bank_name'           => 'nullable|string|max:50',
            'bank_account_number' => 'nullable|string|max:50',
        ]);

        $employee = Employee::create($validated);

        return response()->json(['message' => 'Data karyawan berhasil disimpan.', 'data' => $employee], 201);
    }

    /**
     * Detail karyawan.
     */
    public function show(Employee $employee)
    {
        return response()->json($employee->load(['branch', 'payrolls']));
    }

    /**
     * Update data karyawan.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'nik'                 => 'required|string|max:20|unique:employees,nik,' . $employee->id,
            'name'                => 'required|string|max:255',
            'email'               => 'nullable|email|max:255',
            'position'            => 'required|string|max:100',
            'basic_salary'        => 'required|numeric|min:0',
            'npwp'                => 'nullable|string|max:25',
            'ptkp_status'         => 'required|in:TK/0,TK/1,TK/2,TK/3,K/0,K/1,K/2,K/3',
            'bank_name'           => 'nullable|string|max:50',
            'bank_account_number' => 'nullable|string|max:50',
            'is_active'           => 'required|boolean',
        ]);

        $employee->update($validated);

        return response()->json(['message' => 'Data karyawan berhasil diperbarui.', 'data' => $employee]);
    }
}
