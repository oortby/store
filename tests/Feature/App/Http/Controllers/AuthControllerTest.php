<?php

declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Requests\SignInFormRequest;
use App\Http\Requests\SignUpFormRequest;
use App\Listeners\SendNewUserListener;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Auth\Events\Registered;
use Domain\Auth\Models\User;

final class  AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     **/
    public function it_login_page_success(): void
    {
        $this->get(action([SignInController::class, 'page']))
            ->assertOk()
            ->assertSee('Вход в аккаунт');
    }

    /**
     * @test
     * @return void
     **/
    public function it_sign_up_page_success(): void
    {
        $this->get(action([SignUpController::class, 'page']))
            ->assertOk()
            ->assertSee('Регистрация');
    }

    /**
     * @test
     * @return void
     **/
    public function it_logout_success(): void
    {
        $user = UserFactory::new()->create([
            'email' => 'orion1.by@mail.ru',

        ]);

        $this->actingAs($user)
            ->delete(action([SignInController::class, 'logOut']));

        $this->assertGuest();

    }

    /**
     * @test
     * @return void
     **/
    public function it_forgot_page_success(): void
    {
        $this->get(action([ForgotPasswordController::class, 'page']))
            ->assertOk();
    }

    /**
     * @test
     * @return void
     **/
    public function it_sign_in_success(): void
    {
        $password = '12345678';
        $user = UserFactory::new()->create([
            'email' => 'orion.by@mail.ru',
            'password' => bcrypt($password)
        ]);


        $request = SignInFormRequest::factory()->create([
            'email' => $user->email,
            'password' => $password,
        ]);

        $response = $this->post(
            action([SignInController::class, 'handle']),
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
    public function it_sign_up_success(): void
    {
        Notification::fake();
        Event::fake();

        $request = SignUpFormRequest::factory()->create([
            'email' => 'orion.by@mail.ru',
            'password' => '1234567890',
            'password_confirmation' => '1234567890'
        ]);

         $this->assertDatabaseMissing('users', [
            'email' =>trim($request['email']),
        ]);

        $response = $this->post(
            action([SignUpController::class, 'handle']),
            $request
        );

         $this->assertDatabaseHas('users', [
            'email' => trim($request['email']),
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
        //Notification::assertSentTo($user, NewUserNotification::class);

        $this->assertAuthenticatedAs($user);

        $response
            ->assertValid()
            ->assertRedirect(route('home'));
    }

}