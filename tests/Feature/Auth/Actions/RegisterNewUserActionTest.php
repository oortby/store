<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\Actions;

use Domain\Auth\Actions\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class RegisterNewUserActionTest  extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     **/
    public function it_success_user_created(): void
    {
        $this->assertDatabaseMissing('users',[
            'email'=>'orion.by@mail.ru'
        ]);

        $action = app(RegisterNewUserContract::class);

        $action(NewUserDTO::make('Test','orion.by@mail.ru','12345678'));

        $this->assertDatabaseHas('users',[
            'email'=>'orion.by@mail.ru'
        ]);
    }

}