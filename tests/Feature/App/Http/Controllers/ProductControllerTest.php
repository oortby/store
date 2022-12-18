<?php

declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\ProductController;
use Database\Factories\ProductFactory;
use Tests\TestCase;

final class ProductControllerTest extends TestCase
{
    /**
     * @test
     * @return void
     **/
    public function it_success_response(): void
    {
        $product = ProductFactory::new()
            ->createOne();


        $request = [
            'product' => $product['title'],
        ];

        $this->get(action(ProductController::class,$request))
            ->assertOk();
    }
}