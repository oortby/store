<?php

declare(strict_types=1);

namespace Domain\Order\States;

final class PaidOrderState extends OrderState
{
    protected array $allowedTransitions = [
        CancelledOrderState::class,
    ];
    public function canBeChanged(): bool
    {
        return true;
    }

    public function value(): string
    {
        return 'paid';
    }

    public function humanValue(): string
    {
        return 'Оплачен';
    }
}