<?php
declare(strict_types=1);

namespace App\Providers;

use Domain\Auth\Providers\ActionsServiceProvider;
use Domain\Auth\Providers\AuthServiceProvider;
use Domain\Catalog\Providers\CatalogServiceProvider;
use Domain\Product\Providers\ProductServiceProvider;
use Illuminate\Support\ServiceProvider;

final class  DomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
       $this->app->register(
           AuthServiceProvider::class,
       );

        $this->app->register(
            CatalogServiceProvider::class,
        );

        $this->app->register(
            ProductServiceProvider::class,
        );
    }

}
