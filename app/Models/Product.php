<?php

namespace App\Models;

use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Support\Traits\HasSlug;
use Support\Traits\HasThumbnail;

class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;

    protected $fillable = [
        'slug',
        'title',
        'brand_id',
        'thumbnail',
        'price',
        'on_home_page',
        'sorting'

    ];

    protected function thumbnailDir(): string
    {
       return 'products';
    }

    /**
     * @param Builder $query
     * @return void
     */
    public function scopeHomePage (Builder $query): Builder
    {
        return  $query->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(6);
    }

    public function brand() : BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories() : BelongsToMany
    {

        return $this->belongsToMany(Category::class);
    }


}
