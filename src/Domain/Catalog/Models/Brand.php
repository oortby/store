<?php

namespace Domain\Catalog\Models;

use Database\Factories\BrandFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Support\Traits\HasSlug;
use Support\Traits\HasThumbnail;

class Brand extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;

    protected $fillable = [
        'slug',
        'title',
        'thumbnail',
        'on_home_page',
        'sorting'
    ];

    protected $casts = [
        'on_home_page' => 'boolean'
    ];

    protected function thumbnailDir(): string
    {
        return 'brands';
    }

    /**
     * @param Builder $query
     * @return void
     */
    public function scopeHomePage (Builder $query): Builder
    {
        return $query->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(6);
    }

    public function product(): HasMany
    {
        return $this->hasMany(\App\Models\Product::class);
    }


}
