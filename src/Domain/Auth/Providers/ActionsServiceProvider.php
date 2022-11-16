<?php

declare(strict_types=1);

namespace Domain\Auth\Providers;

use Domain\Auth\Actions\Contracts\RegisterNewUserContract;
use Domain\Auth\Actions\RegisterNewUserAction;
use Illuminate\Support\ServiceProvider;

class ActionsServiceProvider extends ServiceProvider
{
    public array $bindings = [
        RegisterNewUserContract::class=> RegisterNewUserAction::class
    ];
}
