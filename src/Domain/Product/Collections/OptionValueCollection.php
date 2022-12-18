<?php

declare(strict_types=1);

namespace Domain\Product\Collections;

use Illuminate\Support\Collection;

final class OptionValueCollection extends Collection
{
    public function  keyValues(): OptionValueCollection
    {
       return  $this->mapToGroups(static function ($item) {
            return [$item->option->title => $item];
        });
    }
}