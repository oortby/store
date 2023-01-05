<?php

namespace Domain\Order\Providers;

use Domain\Order\Actions\Contracts\NewOrderContract;
use Domain\Order\Actions\NewOrderAction;
use Illuminate\Support\ServiceProvider;

class ActionsServiceProvider extends ServiceProvider
{
    public array $bindings = [
        NewOrderContract::class=> NewOrderAction::class
    ];


}
