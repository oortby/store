<?php

namespace App\Models;

use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;
use Support\Casts\PriceCast;
use Support\Traits\HasSlug;
use Support\Traits\HasThumbnail;

class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;
    use Searchable;

    protected $fillable = [
        'slug',
        'title',
        'text',
        'brand_id',
        'thumbnail',
        'price',
        'on_home_page',
        'sorting'

    ];

    protected $casts = [
        'price' => PriceCast::class,
    ];

   /* #[SearchUsingFullText(['title'])]
    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,

        ];
    }*/

    public function scopeFiltered(Builder $query): void
    {
        $query->when(request('filters.brands'), static function (Builder $q) {
            $q->whereIn('brand_id', request('filters.brands'));
        })->when(request('filters.price'), static function (Builder $q) {
            $q->whereBetween('price', [
                request('filters.price.from'),
                request('filters.price.to'),
            ]);
        });
    }

    public function scopeSorted(Builder $query): void
    {
        $query->when(request('sort'), static function (Builder $q) {
            $column = request()->str('sort');

            if ($column->contains(['price', 'title'])) {
                $direction = $column->contains('-') ? 'DESC' : 'ASC';
                $q->orderBy((string)$column->remove('-'), $direction);
            }
        });
    }

    public function scopeHomePage(Builder $query)
    {
        $query->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(6);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    protected function thumbnailDir(): string
    {
        return 'products';
    }




}
