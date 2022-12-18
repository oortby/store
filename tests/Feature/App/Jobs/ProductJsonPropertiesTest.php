<?php

declare(strict_types=1);

namespace Tests\Feature\App\Jobs;

use App\Jobs\ProductJsonProperties;
use Database\Factories\ProductFactory;
use Database\Factories\PropertyFactory;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

final class ProductJsonPropertiesTest extends TestCase
{
    /**
     * @test
     * @return void
     **/
    public function it_created_json_properties(): void
    {
        $queue = Queue::getFacadeRoot();
        Queue::fake([ProductJsonProperties::class]);

        $properties = PropertyFactory::new()
            ->count(10)
            ->create();

        $product = ProductFactory::new()
            ->hasAttached($properties , static function () {
                return ['value' => fake()->word()];
            })
        ->create();

        $this->assertEmpty($product->json_properties);

        // Вернем реальные instance
        Queue::swap($queue);

        ProductJsonProperties::dispatchSync($product);

        // обновление всех связей
        $product->refresh();

        $this->assertNotEmpty($product->json_properties);
    }
}