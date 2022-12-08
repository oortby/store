<?php

declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers;

use Database\Factories\ProductFactory;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class ThumbnailControllerTest extends TestCase
{
    public function it_generated_success(): void
    {
        $size = '500x500';
        $method = 'resize';
        $storage = Storage::disk('images');

        config()->set('thumbnail', ['allowed_sizes' => [$size]]);
        $product = ProductFactory::new()->create();
        $response = $this->get($product->makeThubnail($size, $method));

        $response->assertOk();

        $storage->assertExists(
            "products.$method/$size" . \File::basename($product->thumbnail)
        );
    }
}