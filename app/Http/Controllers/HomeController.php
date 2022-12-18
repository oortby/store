<?php

namespace App\Http\Controllers;

use Domain\Catalog\Models\Brand;
use Domain\Catalog\ViewModels\BrandViewModel;
use Domain\Catalog\ViewModels\CategoryViewModel;
use Domain\Catalog\ViewModels\ProductViewModel;
use Domain\Product\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    public function page(): Factory|View|Application|RedirectResponse
    {
        $products = Product::query()
            ->homePage()
            ->get();

        return view('index', [
            'categories' => CategoryViewModel::make()->homePage(),
            'brands' => BrandViewModel::make()->homePage(),
            'products'=> $products,
        ]);
    }
}
