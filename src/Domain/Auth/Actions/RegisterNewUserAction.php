<?php

declare(strict_types=1);

namespace Domain\Auth\Actions;


use Domain\Auth\Actions\Contracts\RegisterNewUserContract;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;

final class RegisterNewUserAction implements RegisterNewUserContract
{
    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @return void
     */
    public function __invoke(string $name, string $email, string $password): void
    {
        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        event(new Registered($user));

        auth()->login($user);
    }
}