<?php

namespace App\Filters;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ThreadFilters
{
    private $request;

    public function __construct(Request $request)
    {

        $this->request = $request;
    }

    public function apply(Builder $builder)
    {
        if (!$username = $this->request->query('by')) return $builder;

        $user = User::where('name', $username)->firstOrFail();

        return $builder->where('user_id', $user->id);
    }
}
