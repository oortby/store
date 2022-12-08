<?php

namespace Support\Testing;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;

class FakerImageProvider extends Base
{

    public function fixturesImage(string $fixriresDir, string $storageDir): string
    {
        $storage = Storage::disk('images');


        if (!$storage->exists($storageDir)) {
            $storage->makeDirectory($storageDir);
        }
        $file = $this->generator->file(
            base_path("tests/Fixture/images/$fixriresDir"),
            Storage::disk('images')->path($storageDir),
            false

        );

        return '/storage/images/' . trim($storageDir, '/') . '/' . $file;
    }

}
