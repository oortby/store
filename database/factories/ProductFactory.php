<?php

declare(strict_types=1);
namespace Database\Factories;

use Closure;
use Domain\Catalog\Models\Brand;
use Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ProductFactory extends Factory
{
    protected $model = Product::class;
    public function definition() : array
    {
        return [
            'title' => ucfirst($this->faker->words(2,true)),
            'text' => $this->faker->realText(),
            'brand_id'=> Brand::query()->inRandomOrder()->value('id'),
            'thumbnail' =>$this->faker->fixturesImage('products','products'),
            'price'=> $this->faker->numberBetween(100000,1000000),
            'on_home_page' =>$this->faker->boolean(),
            'sorting'   => $this->faker->numberBetween(1,999),
        ];
    }
}
