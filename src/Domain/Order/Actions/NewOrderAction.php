<?php

declare(strict_types=1);

namespace Domain\Order\Actions;

use Domain\Auth\Actions\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Domain\Order\Actions\Contracts\NewOrderContract;
use Domain\Order\DTOs\NewOrderDTO;
use Domain\Order\Models\Order;

final class NewOrderAction implements NewOrderContract
{
    public function __invoke(NewOrderDTO $data): Order
    {
        $registerAction = app(RegisterNewUserContract::class);

        $customer = $data->customer;

        if ( $data->create_account) {
            $registerAction(NewUserDTO::make(
                $customer['first_name'] . '' . $customer['last_name'],
                $customer['email'],
                $data->password
            ));
        }

        return Order::query()->create([
            'user_id'           => auth()->id(),
            'payment_method_id' => $data->payment_method_id,
            'delivery_type_id'  => $data->delivery_type_id,
        ]);
    }
}