<?php

namespace App\Models;

use App\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Journal extends Model
{
    use HasFactory, BelongsToBranch;

    protected $fillable = [
        'branch_id',
        'transaction_date',
        'reference_number',
        'description',
        'status',
        'created_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(JournalItem::class);
    }

    public function taxLogs(): HasMany
    {
        return $this->hasMany(TaxLog::class);
    }

    public function payroll(): HasOne
    {
        return $this->hasOne(Payroll::class);
    }
}
