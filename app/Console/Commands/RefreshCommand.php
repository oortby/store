<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class  RefreshCommand extends Command
{
    protected $signature = 'store:refresh';

    protected $description = 'Refresh';

    public function handle(): int
    {
        if (app()->isProduction()) {
            return self::FAILURE;
        }

        $this->call('cache:clear');
        $this->call('view:clear');
        $this->call('config:clear');

        Storage::deleteDirectory('images/products');
        Storage::deleteDirectory('images/brands');


        $this->call('migrate:refresh',[
                '--seed'=> true
            ]);



        return self::SUCCESS;
    }
}
