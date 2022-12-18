<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class CatalogController extends Controller
{
    public function __invoke(?Category $category): Factory|View|Application
    {
        $categories = Category::query()
            ->select(['id', 'title', 'slug'])
            ->has('products')
            ->get();

        /*
         * // Вариант со Scope
         *  $products = Product::search()
             ->query(function (Builder $query) use ($category) {
                 $query->select(['id', 'title', 'slug', 'price', 'thumbnail'])
                     ->when($category->exists, function (Builder $query) use ($category) {
                         $query->whereRelation(
                             'categories',
                             'categories.id',
                             '=',
                             $category->id
                         );
                     })
                     ->filtered()
                     ->sorted();

             })
             ->paginate(6);*/


        $products = Product::query()
            ->select(['id', 'title', 'slug', 'price', 'thumbnail','json_properties'])
            ->when(request('s'), static function (Builder $query) {
                $query->whereFullText(['title','text'], request('s') );
            })
            ->when($category->exists, static function (Builder $query) use ($category) {
                $query->whereRelation(
                    'categories',
                    'categories.id',
                    '=',
                    $category->id
                );
            })
            ->filtered()
            ->sorted()
            ->paginate(6);

        return view(
            'catalog.index',
            compact(
                'categories',
                'products',
                'category'
            )
        );
    }
}
