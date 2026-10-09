<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\Journal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'branch_id',
        'name',
        'email',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function createdJournals(): HasMany
    {
        return $this->hasMany(Journal::class, 'created_by');
    }

    /**
     * Helper Pengecekan Akses Cabang
     */
    public function hasAccessToBranch(?int $branchId): bool
    {
        if ($this->hasRole('Admin') || $this->can('view-reports-all-branches')) {
            return true;
        }

        return (int) $this->branch_id === (int) $branchId;
    }
}
