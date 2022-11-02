<?php

namespace App\Providers;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;

class FakerImageProvider extends Base
{

    public function fixturesImage(string $fixriresDir, string $storageDir): string
    {
        if (!Storage::exists($storageDir)) {
            Storage::makeDirectory($storageDir);
        }

        $file = $this->generator->file(
            base_path("tests/Fixture/images/$fixriresDir"),
            Storage::path($storageDir),
            false

        );

        return '/storage/' . trim($storageDir, '/') . '/' . $file;
    }

}
