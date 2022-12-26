<?php

declare(strict_types=1);

namespace App\Routing;

use App\Contracts\RouteRegistrar;
use App\Http\Controllers\CartController;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Facades\Route;

final class CartRegistrar implements RouteRegistrar
{
    public function map(Registrar $registrar): void
    {
        Route::middleware('web')->group(static function () {
            Route::prefix('cart')
                ->group(static function () {
                    Route::get('/', [CartController::class, 'index'])->name('cart');
                    Route::post('/{product}/add', [CartController::class, 'add'])->name('cart.add');
                    Route::post('/{item}/quantity', [CartController::class, 'quantity'])->name('cart.quantity');
                    Route::delete('/{item}/delete', [CartController::class, 'delete'])->name('cart.delete');
                    Route::delete('/truncate', [CartController::class, 'truncate'])->name('cart.truncate');
                });
        });
    }
}
