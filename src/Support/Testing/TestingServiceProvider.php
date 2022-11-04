<?php
declare(strict_types=1);

namespace Support\Testing;

use App\Providers\FakerImageProvider;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;

final class TestingServiceProvider extends ServiceProvider
{

    public function register() :void
    {
        $this->app->singleton(Generator::class, static function(){
            $faker = Factory::create();
            $faker->addProvider (new FakerImageProvider($faker));
            return $faker;
        });
    }

    public function boot()
    {
        //
    }
}
