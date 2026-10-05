<?php

namespace App\Http\Controllers;

use App\Services\FinancialReportService;
use Illuminate\Http\Request;

class FinancialReportController extends Controller
{
    protected $reportService;

    public function __construct(FinancialReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Neraca Saldo (Trial Balance).
     */
    public function trialBalance(Request $request)
    {
        $request->validate([
            'branch_id' => 'nullable|exists:branches,id',
            'date'      => 'nullable|date',
        ]);

        $report = $this->reportService->getTrialBalance(
            $request->branch_id,
            $request->date ?? date('Y-m-d')
        );

        return response()->json($report);
    }

    /**
     * Laporan Laba Rugi (Income Statement).
     */
    public function incomeStatement(Request $request)
    {
        $request->validate([
            'branch_id'  => 'nullable|exists:branches,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $report = $this->reportService->getIncomeStatement(
            $request->branch_id,
            $request->start_date,
            $request->end_date
        );

        return response()->json($report);
    }
}
