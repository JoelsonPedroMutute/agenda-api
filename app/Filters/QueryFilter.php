<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Arr;

use Illuminate\Contracts\Database\Eloquent\Builder;

class QueryFilter
{
    protected $request;
    protected $builder;
    /**
     * Create a new class instance.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function apply(Builder $builder)
    {
        $this->builder = $builder;
        foreach ($this->filters() as $name => $value) {
        if (method_exists($this, $name) ) {
            $this->$name($value);
        }

        }
        return $this->builder;
    }
    protected function filters(): array
    {
        return $this->request->request->all();
    }
}
