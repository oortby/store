<?php
declare(strict_types=1);

namespace App\Providers;

use Domain\Auth\Providers\ActionsServiceProvider;
use Domain\Auth\Providers\AuthServiceProvider;
use Illuminate\Support\ServiceProvider;

final class  DomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
       $this->app->register(
           AuthServiceProvider::class,
           ActionsServiceProvider::class
       );
    }

}
