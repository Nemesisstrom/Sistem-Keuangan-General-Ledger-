<?php

namespace App\Models;

use App\Models\TaxLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category',
        'rate',
        'account_id',
        'is_active',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    public function taxLogs(): HasMany
    {
        return $this->hasMany(TaxLog::class);
    }
}
