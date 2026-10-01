<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Trait BelongsToBranch
 *
 * @mixin Model
 */
trait BelongsToBranch
{
    /**
     * Boot the BelongsToBranch trait for a model.
     *
     * @return void
     */
    protected static function bootBelongsToBranch()
    {
        /** @var \Illuminate\Database\Eloquent\Model $class */
        $class = static::class;

        // 1. Filter otomatis berdasarkan branch_id milik user login
        $class::addGlobalScope('branch', function (Builder $builder) {
            if (Auth::check() && Auth::user()?->branch_id) {
                $builder->where('branch_id', Auth::user()->branch_id);
            }
        });

        // 2. Set branch_id otomatis saat membuat record baru
        $class::creating(function ($model) {
            if (Auth::check() && empty($model->branch_id)) {
                $model->branch_id = Auth::user()->branch_id;
            }
        });
    }

    /**
     * Scope query tanpa memfilter branch (untuk Laporan Konsolidasi)
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithoutBranchScope(Builder $query): Builder
    {
        return $query->withoutGlobalScope('branch');
    }
}
