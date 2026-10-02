<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Rekap Absensi.
     */
    public function index(Request $request)
    {
        $request->validate([
            'date'  => 'nullable|date',
            'month' => 'nullable|string', // Format: YYYY-MM
        ]);

        $attendances = Attendance::with('employee')
            ->when($request->date, function ($query, $date) {
                $query->where('date', $date);
            })
            ->when($request->month, function ($query, $month) {
                $query->where('date', 'like', "{$month}%");
            })
            ->latest('date')
            ->paginate(20);

        return response()->json($attendances);
    }

    /**
     * Catat absensi karyawan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'  => 'required|exists:employees,id',
            'date'         => 'required|date',
            'clock_in'     => 'nullable|date_format:H:i',
            'clock_out'    => 'nullable|date_format:H:i',
            'status'       => 'required|in:present,late,absent,leave,sick',
            'late_minutes' => 'nullable|integer|min:0',
        ]);

        $attendance = Attendance::updateOrCreate(
            ['employee_id' => $validated['employee_id'], 'date' => $validated['date']],
            $validated
        );

        return response()->json(['message' => 'Absensi berhasil dicatat.', 'data' => $attendance]);
    }
}
