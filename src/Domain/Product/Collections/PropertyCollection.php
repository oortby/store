<?php

declare(strict_types=1);

namespace Domain\Product\Collections;

use Illuminate\Support\Collection;

final class PropertyCollection extends Collection
{
    public function keyValues(): PropertyCollection
    {
        return $this->mapWithKeys(fn($property) => [$property->title => $property->pivot->value]);
    }
}