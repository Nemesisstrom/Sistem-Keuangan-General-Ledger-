<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BalanceSheetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'as_of_date' => $this['as_of_date'],
            'assets' => [
                'items' => collect($this['assets'])->map(function ($item) {
                    return [
                        'account_code' => $item['account_code'],
                        'account_name' => $item['account_name'],
                        'amount'       => (float) $item['amount'],
                    ];
                })->values()->all(),
                'total' => (float) $this['total_assets'],
            ],
            'liabilities' => [
                'items' => collect($this['liabilities'])->map(function ($item) {
                    return [
                        'account_code' => $item['account_code'],
                        'account_name' => $item['account_name'],
                        'amount'       => (float) $item['amount'],
                    ];
                })->values()->all(),
                'total' => (float) $this['total_liabilities'],
            ],
            'equity' => [
                'items' => collect($this['equity'])->map(function ($item) {
                    return [
                        'account_code' => $item['account_code'],
                        'account_name' => $item['account_name'],
                        'amount'       => (float) $item['amount'],
                    ];
                })->values()->all(),
                'total' => (float) $this['total_equity'],
            ],
            'summary' => [
                'total_liabilities_and_equity' => (float) $this['total_liabilities_and_equity'],
                'is_balanced'                  => (bool) $this['is_balanced'],
            ],
        ];
    }
}
