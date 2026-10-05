<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrialBalanceResource extends JsonResource
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
            'summary' => [
                'total_debit'  => (float) $this['grand_total_debit'],
                'total_credit' => (float) $this['grand_total_credit'],
                'is_balanced'  => (bool) $this['is_balanced'],
            ],
            'items' => collect($this['items'])->map(function ($item) {
                return [
                    'account_id'     => $item['account_id'],
                    'account_code'   => $item['account_code'],
                    'account_name'   => $item['account_name'],
                    'normal_balance' => $item['normal_balance'],
                    'total_debit'    => (float) $item['total_debit'],
                    'total_credit'   => (float) $item['total_credit'],
                    'net_balance'    => (float) $item['net_balance'],
                ];
            })->values()->all(),
        ];
    }
}
