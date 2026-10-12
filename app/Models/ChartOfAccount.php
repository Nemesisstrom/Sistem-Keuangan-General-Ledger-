<?php

namespace App\Models;

use App\Models\Tax;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $table = 'chart_of_accounts';

    protected $fillable = [
        'code',
        'name',
        'type',
        'normal_balance',
        'parent_id',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Akun Induk (Parent Account)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_id');
    }

    /**
     * Relasi ke Akun Anak (Child Accounts)
     */
    public function children(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id');
    }

    /**
     * Relasi ke Item Jurnal Transaksi
     */
    public function journalItems(): HasMany
    {
        return $this->hasMany(JournalItem::class, 'account_id');
    }

    /**
     * Relasi ke Pengaturan Pajak
     */
    public function taxes(): HasMany
    {
        return $this->hasMany(Tax::class, 'account_id');
    }
}
