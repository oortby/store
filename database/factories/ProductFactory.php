<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;


class ProductFactory extends Factory
{
    public function definition() : array
    {
        return [
            'title' => ucfirst($this->faker->words(2,true)),
            'brand_id'=> Brand::query()->inRandomOrder()->value('id'),
            'thumbnail' =>$this->faker->file(
                base_path('/tests/Fixture/images/products'),
                storage_path('app/public/images/products')),
            'price'=> $this->faker->numberBetween(1000,10000)
        ];
    }
}
