<?php

declare(strict_types=1);

namespace Domain\Auth\Providers;

use Domain\Auth\Actions\Contacts\RegisterNewUserContract;
use Domain\Auth\Actions\RegisterNewUserAction;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class ActionsServiceProvider extends ServiceProvider
{

    public array $bindings = [
        RegisterNewUserContract::class=> RegisterNewUserAction::class
    ];
}
