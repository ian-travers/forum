<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Filters
{
    protected $request;
    /**
     * @var Builder
     */
    protected $builder;

    protected $filters = [];

    public function __construct(Request $request)
    {

        $this->request = $request;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->filters as $filter) {
            if (method_exists($this, $filter) && $this->request->has($filter)) {
                $this->$filter($this->request->query($filter));
            }
        }

        return $builder;
    }
}
