<?php

declare(strict_types=1);

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $item) {
            $item->makeSlug();
        });
    }

    protected function makeSlug(): void
    {
        if (!$this->{$this->slugColumn()}) {
            $slug = $this->slugUnique(
                str($this->{$this->slugFrom()})
                    ->slug()
                    ->value()
            );
            $this->{$this->slugColumn()} = $slug;
        }
    }

    protected function slugFrom(): string
    {
        return 'title';
    }

    protected function slugColumn(): string
    {
        return 'slug';
    }

    private function slugUnique(string $slug): string
    {
        $originSlug = $slug;
        $i = 0;
        while ($this->isSlugExists($slug)) {
            $i++;
            $slug = $originSlug . '-' . $i;
            dd($slug);
        }
        return $slug;
    }

    private function isSlugExists(string $slug): bool
    {
        $query = $this->NewQuery()
            ->where($this->slugColumn(), $slug)
            ->where($this->getKeyName(), '!=', $this->getKey())
            ->withoutGlobalScopes();

        return $query->exists();
    }
}