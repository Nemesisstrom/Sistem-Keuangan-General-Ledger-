<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomeStatementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'period' => [
                'start_date' => $this['period']['start_date'] ?? null,
                'end_date'   => $this['period']['end_date'] ?? null,
            ],
            'revenues' => [
                'items' => collect($this['revenues'])->map(function ($item) {
                    return [
                        'account_code' => $item['account_code'],
                        'account_name' => $item['account_name'],
                        'amount'       => (float) $item['amount'],
                    ];
                })->values()->all(),
                'total' => (float) $this['total_revenue'],
            ],
            'expenses' => [
                'items' => collect($this['expenses'])->map(function ($item) {
                    return [
                        'account_code' => $item['account_code'],
                        'account_name' => $item['account_name'],
                        'amount'       => (float) $item['amount'],
                    ];
                })->values()->all(),
                'total' => (float) $this['total_expense'],
            ],
            'summary' => [
                'net_profit' => (float) $this['net_profit'],
                'status'     => $this['status'],
            ],
        ];
    }
}
