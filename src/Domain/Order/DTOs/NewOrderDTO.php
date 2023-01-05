<?php

declare(strict_types=1);

namespace Domain\Order\DTOs;

use Illuminate\Http\Request;
use Support\Traits\Makeable;

final class NewOrderDTO
{
    use Makeable;

    public function __construct(
        public readonly array $customer,
        public readonly string $delivery_type_id,
        public readonly string $payment_method_id,
        public readonly bool $create_account = false,
        public readonly ?string $password = null,
    ) {}

    public static function fromRequest(Request $request): NewOrderDTO
    {
        return static::make(...$request->only(
            'customer',
            'delivery_type_id',
            'payment_method_id',
            'create_account',
            'password',
        ));
    }
}