<?php

declare(strict_types=1);

namespace Domain\Catalog\ViewModels;

use Domain\Catalog\Models\Brand;
use Illuminate\Support\Facades\Cache;
use Support\Traits\Makeable;

final class BrandViewModel
{
    use Makeable;

    public function homePage(): mixed
    {
       return  Cache::tags(['brand'])->rememberForever('brand_home_page', static function(){
            return Brand::query()
                ->homePage()
                ->get();
        });


    }
}