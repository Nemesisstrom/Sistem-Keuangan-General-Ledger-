<?php

namespace App\Models;

use App\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxLog extends Model
{
    use BelongsToBranch, HasFactory;

    protected $fillable = [
        'branch_id',
        'journal_entry_id',
        'tax_id',
        'type',
        'taxable_amount',
        'tax_amount',
        'tax_invoice_number',
    ];

    protected $casts = [
        'taxable_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class, 'journal_entry_id');
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }
}
