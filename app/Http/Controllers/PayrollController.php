<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use App\Services\PayrollService;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    /**
     * Daftar Histori Payroll.
     */
    public function index(Request $request)
    {
        $payrolls = Payroll::with(['employee', 'branch', 'journal'])
            ->when($request->period, function ($query, $period) {
                $query->where('period', $period);
            })
            ->latest('period')
            ->paginate(15);

        return response()->json($payrolls);
    }

    /**
     * Proses Gaji Karyawan & Autopost Jurnal Keuangan.
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period'      => 'required|string|regex:/^\d{4}-\d{2}$/', // Format: YYYY-MM
            'allowances'  => 'nullable|numeric|min:0',
            'deductions'  => 'nullable|numeric|min:0',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);

        // Mencegah double process gaji di periode yang sama
        $exists = Payroll::where('employee_id', $employee->id)
            ->where('period', $validated['period'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Gaji karyawan untuk periode ini sudah pernah diproses.'], 422);
        }

        $payroll = $this->payrollService->processPayroll(
            $employee,
            $validated['period'],
            (float) ($validated['allowances'] ?? 0),
            (float) ($validated['deductions'] ?? 0)
        );

        return response()->json([
            'message' => 'Penggajian berhasil diproses dan jurnal keuangan dibuat.',
            'data'    => $payroll->load(['employee', 'journal.items']),
        ], 201);
    }
}
