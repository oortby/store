<?php
declare(strict_types=1);

namespace Domain\Auth\Actions;

use Domain\Auth\Actions\Contacts\RegisterNewUserContract;
use Illuminate\Auth\Events\Registered;

final class RegisterNewAction implements RegisterNewUserContract
{
    public function __invoke()
    {
        $user = User::query()->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);

        event(new Registered($user));

        auth()->login($user);
    }
}