<?php

declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers;

use Database\Factories\ProductFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Tests\TestCase;

final class ThumbnailControllerTest extends TestCase
{
    use RefreshDatabase;

   /* public function it_generated_image_success(): void
    {
        $size = '500x500';
        $method = 'resize';
        $storage = Storage::disk('images');

        config()->set('thumbnail', ['allowed_sizes' => [$size]]);
        $product = ProductFactory::new()->create();
        $response = $this->get($product->makeThumbnail($size, $method));

       //Image::shouldReceive('make')
       //     ->once()
       //     ->andReturnSelf()
       //     ->shouldReceive('resize')
       //     ->once()
       //     ->andReturnSelf()
       //     ->shouldReceive('save')
       //     ->once()
       //     ->andReturn();

        $response->assertOk();

        $storage->assertExists(
            "products/$method/$size" . \File::basename($product->thumbnail)
        );
    }*/
}