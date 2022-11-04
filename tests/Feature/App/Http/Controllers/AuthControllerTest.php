<?php

declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\Auth\SignInController;
use App\Http\Requests\SignInFormRequest;
use App\Http\Requests\SignUpFormRequest;
use App\Listeners\SendNewUserListener;
use App\Notifications\NewUserNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use User;

final class  AuthControllerTest extends TestCase
{
    use RefreshDatabase;


    /**
     * @test
     * @return void
     **/
    public function it_login_page_success(): void
    {
        $this->get(action([SignInController::class, 'index']))
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewHas('auth.index');
    }

    /**
     * @test
     * @return void
     **/
    public function it_sign_up_page_success(): void
    {
        $this->get(action([SignInController::class, 'signUp']))
            ->assertOk()
            ->assertSee('Регистрация')
            ->assertViewHas('auth.sign-up');
    }

    /**
     * @test
     * @return void
     **/
    public function it_logout_success(): void
    {
        $user = User::factory()->create([
            'email' => 'orion.by@mail.ru',

        ]);

        $this->assertAuthenticatedAs($user)
            ->delete(action([SignInController::class, 'logOut']));

        $this->assertGuest();

    }

    /**
     * @test
     * @return void
     **/
    public function it_forgot_page_success(): void
    {
        $this->get(action([SignInController::class, 'forgot']))
            ->assertOk()
            ->assertViewHas('auth.forgot-password');
    }

    /**
     * @test
     * @return void
     **/
    public function it_sign_in_success(): void
    {
        $password = '12345678';
        $user = User::factory()->create([
            'email' => 'orion.by@mail.ru',
            'password' => bcrypt($password)
        ]);
        $request = SignInFormRequest::factory()->create([
            'email' => $user->email,
            'pasword' => $user->password,
        ]);

        $response = $this->post(
            action([SignInController::class, 'signIn']),
            $request
        );

        $response->assertValid()
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     * @return void
     **/
    public function it_store_success(): void
    {
        Notification::fake();
        Event::fake();

        $request = SignUpFormRequest::factory()->create([
            'email' => 'orion.by@mail.ru',
            'pasword' => '12345678',
            'password_confirmation' => '123'
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => $request['email'],
        ]);

        $response = $this->post(
            action([SignInController::class, 'store']),
            $request
        );

        $this->assertDatabaseHas('users', [
            'email' => $request['email'],
        ]);

        $user = User::query()
            ->where('email', $request['email'])
            ->first();

        Event::assertDispatched(Registered::class);
        Event::assertListening(
            Registered::class,
            SendNewUserListener::class
        );

        $event = new Registered($user);
        $listener = new SendNewUserListener();
        $listener->handle($event);


        // Вызывается  на очередях
        Notification::assertSentTo($user, NewUserNotification::class);

        $this->assertAuthenticatedAs($user);

        $response
            ->assertValid()
            ->assertRedirect(route('home'));
    }

}