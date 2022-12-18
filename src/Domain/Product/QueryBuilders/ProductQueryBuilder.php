<?php

declare(strict_types=1);

namespace Domain\Product\QueryBuilders;

use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pipeline\Pipeline;

final class ProductQueryBuilder extends Builder
{
    public function homePage(): ProductQueryBuilder
    {
        return $this->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(6);
    }

    /* #[SearchUsingFullText(['title'])]
     public function toSearchableArray(): array
     {
         return [
             'title' => $this->title,
         ];
     }*/

    public function filtered(): ProductQueryBuilder
    {
        return app(Pipeline::class)
            ->send($this)
            ->through(filters())
            // Вместо via с указанием метода, можно использовать __invoke() по умолчанию
            //->via('handle')
            ->thenReturn();

        /* II Вариант (через  helper)
         // Через App/Providers/CatalogServiceProvider(FilterManager)
         foreach(filters()  as $filter) {
            $query = $filter->apply($query);
        }*/
        /*  I вариант
        $query->when(request('filters.brands'), static function (Builder $q) {
             $q->whereIn('brand_id', request('filters.brands'));
         })->when(request('filters.price'), static function (Builder $q) {
             $q->whereBetween('price', [
                 request('filters.price.from', 0) * 100,
                 request('filters.price.to', 100000) * 100,
             ]);
         });*/
    }

    public function sorted(): Builder|ProductQueryBuilder
    {
        // через  helper
        return sorter()->run($this);

        // через Facade
        // Sorter::run($query);
        /*$query->when(request('sort'), static function (Builder $q) {
            $column = request()->str('sort');

            if ($column->contains(['price', 'title'])) {
                $direction = $column->contains('-') ? 'DESC' : 'ASC';
                $q->orderBy((string) $column->remove('-'), $direction);
            }
        });*/
    }

    public function withCategory(Category $category): ProductQueryBuilder
    {
        return $this->when($category->exists, static function (Builder $query) use ($category) {
            $query->whereRelation(
                'categories',
                'categories.id',
                '=',
                $category->id
            );
        });
    }

    public function search(): ProductQueryBuilder
    {
        return $this->when(request('s'), static function (Builder $query) {
            $query->whereFullText(['title', 'text'], request('s'));
        });
    }
}