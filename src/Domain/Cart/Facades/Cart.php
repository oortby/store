<?php

declare(strict_types=1);

namespace Domain\Cart\Facades;

use Domain\Cart\CartManager;
use Domain\Product\Models\Product;
use Illuminate\Support\Facades\Facade;

/**
 * @method static add(Product $product, mixed $request)
 */
final class Cart extends Facade
{
    /**
     * @see CartManager
     */
    protected static function getFacadeAccessor(): string
    {
        return CartManager::class;
    }
}