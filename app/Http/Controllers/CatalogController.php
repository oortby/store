<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\View\ViewModels\CatalogViewModel;
use Domain\Catalog\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class CatalogController extends Controller
{
    public function __invoke(?Category $category): Factory|View|Application
    {
        return view('catalog.index', new CatalogViewModel($category));

        /* return view('catalog.index', [
                 'products'   => CatalogViewModel::class->products($category),
                 'categories' => CatalogViewModel::class->categories(),
                 'category'   => $category,
             ]
         );*/
    }
}
