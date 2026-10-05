<?php

namespace App\Http\Controllers;

use App\Http\Resources\BalanceSheetResource;
use App\Http\Resources\IncomeStatementResource;
use App\Http\Resources\TrialBalanceResource;
use App\Services\FinancialReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected FinancialReportService $reportService;

    public function __construct(FinancialReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function trialBalance(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'branch_id'  => 'nullable|exists:branches,id',
        ]);

        $report = $this->reportService->getTrialBalance(
            $request->query('start_date'),
            $request->query('end_date'),
            $request->query('branch_id')
        );

        return new TrialBalanceResource($report);
    }

    public function incomeStatement(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'branch_id'  => 'nullable|exists:branches,id',
        ]);

        $report = $this->reportService->getIncomeStatement(
            $request->query('start_date'),
            $request->query('end_date'),
            $request->query('branch_id')
        );

        return new IncomeStatementResource($report);
    }

    public function balanceSheet(Request $request)
    {
        $request->validate([
            'as_of_date' => 'nullable|date',
            'branch_id'  => 'nullable|exists:branches,id',
        ]);

        $report = $this->reportService->getBalanceSheet(
            $request->query('as_of_date'),
            $request->query('branch_id')
        );

        return new BalanceSheetResource($report);
    }
}
