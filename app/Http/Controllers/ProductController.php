<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Domain\Product\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function __invoke(Product $product): Factory|View|Application
    {
        $product->load(['optionValues.option']);

        if (session('also')) {
            $also = Product::query()
                ->where(static function ($q) use ($product) {
                    $q->whereIn('id', session('also'))
                        ->where('id', '<>', $product->id);
                })
                ->get();
        }

        session()->put('also.' . $product->id, $product->id);

        $options = $product->optionValues->mapToGroups(static function ($item) {
            return [$item->option->title => $item];
        });

        return view(
            'product.show',
            [
                'product' => $product,
                'options' => $options,
                'also'    => $also ?? null,
            ]
        );
    }
}
