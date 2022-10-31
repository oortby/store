<?php

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Model::shouldBeStrict(!app()->isProduction());

        if (app()->isProduction()){

            DB::listen(function ($query){
                if($query->time > 100) {
                    logger()->channel('telegram')
                        ->debug('query longer than 1ms: '.$query->sql ,$query->bindings);
                }
            });

            $kernel = app(Kernel::class);

            $kernel->whenRequestLifecycleIsLongerThan(
                CarbonInterval::second(4),
                function(Connection $connection){
                    logger()->channel('telegram')
                        ->debug('whenRequestLifecycleIsLongerThan: '. request()->url());
                }
            );

        }

    }
}
