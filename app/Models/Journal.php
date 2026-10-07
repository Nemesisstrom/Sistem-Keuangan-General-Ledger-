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
    use BelongsToBranch, HasFactory;

    protected $table = 'journal_entries';

    protected $fillable = [
        'branch_id',
        'entry_number',
        'date',
        'description',
        'status',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user(): BelongsTo
    {
        return $this->creator();
    }

    public function items(): HasMany
    {
        return $this->hasMany(JournalItem::class, 'journal_entry_id');
    }

    public function taxLogs(): HasMany
    {
        return $this->hasMany(TaxLog::class, 'journal_entry_id');
    }

    public function payroll(): HasOne
    {
        return $this->hasOne(Payroll::class, 'journal_entry_id');
    }
}
