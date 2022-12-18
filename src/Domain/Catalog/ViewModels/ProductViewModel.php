<?php

declare(strict_types=1);

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\Brand;
use Domain\Product\Models\Product;
use Illuminate\Support\Facades\Cache;
use Support\Traits\Makeable;

final class ProductViewModel
{
    use Makeable;

    public function homePage(): mixed
    {
       return  Cache::tags(['product'])->rememberForever('product_home_page', static function(){
            return Product::query()
                ->homePage()
                ->get();
        });


    }
}