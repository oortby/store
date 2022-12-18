<?php

declare(strict_types=1);

namespace Domain\Product\Collections;

use Illuminate\Support\Collection;

final class OptionvalueCollection extends Collection
{

  public function
  keyValues(){
$options = $product->optionValues->mapToGroups(static function ($item) {
    return [$item->option->title => $item];
});
}
}