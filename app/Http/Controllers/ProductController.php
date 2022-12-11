<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function __invoke(Product $product): Factory|View|Application
    {
        $product->load(['optionValues.option']);

        // Просмотренные товары
        /* $arSeeProductIds = [];
         $productId = $product->id;
         if (session()->pull('also.' . $product->id)) {
             $seeProduct = Product::query()
                 ->whereIn('id', session()->pull('also.' . $productId))
                 ->get()
                 ->keyBy('id');
             unset($seeProduct->$productId);
             $arSeeProductIds = array_keys($seeProduct->toArray());
         }

         $nextArSeeProductIds = array_merge($arSeeProductIds, [$product->id]);
         session()->put('also.' . $product->id, $nextArSeeProductIds);
        */

        $options = $product->optionValues->mapToGroups(static function ($item) {
            return [$item->option->title => $item];
        });

        return view(
            'product.show',
            [
                'product' => $product,
                'options' => $options,
            ]
        );
    }
}
