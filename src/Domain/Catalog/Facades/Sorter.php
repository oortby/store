<?php

declare(strict_types=1);

namespace Domain\Catalog\Facades;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Facade;

final class Sorter extends Facade
{
    /**
     * @method static Builder  run(Builder $query)
     * @see \Domain\Catalog\Sorters\Sorter
     */
    protected static function getFacadeAccessor(): string
    {
        return \Domain\Catalog\Sorters\Sorter::class;
    }
}