<?php

declare(strict_types=1);

namespace App\Routing;

use App\Contracts\RouteRegistrar;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\CatalogViewMiddleware;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Facades\Route;

final class ProductRegistrar implements RouteRegistrar
{
    public function map(Registrar $registrar): void
    {
        Route::middleware('web')->group( static function () {

            Route::middleware('web')
                ->group( function() {
                    Route::get('/product/{product:slug}', ProductController::class)
                        ->name('product');

                });
        });
    }
}
