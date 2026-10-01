<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class BranchScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if ($user && $user->role !== 'superadmin' && $user->branch_id) {
            $builder->where($model->getTable() . '.branch_id', $user->branch_id);
        }
    }
}
