<?php

namespace App\Filters;

use App\User;
use Illuminate\Database\Eloquent\Builder;

class ThreadFilters extends Filters
{
    protected $filters = ['by'];

    protected function by(string $username): Builder
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }
}
