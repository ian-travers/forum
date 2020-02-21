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

        foreach ($this->getFilters() as $filter => $value) {

            if (!$this->hasFilter($filter)) return;

            $this->$filter($value);
        }

        return $builder;
    }

    protected function getFilters()
    {
        return $this->request->only($this->filters);
    }

    protected function hasFilter($filter): bool
    {
        return method_exists($this, $filter) && $this->request->query($filter);
    }
}
