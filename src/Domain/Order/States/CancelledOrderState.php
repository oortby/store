<?php

declare(strict_types=1);

namespace Domain\Order\States;

final class CancelledOrderState extends OrderState
{
    protected array $allowedTransitions = [
    ];
    public function canBeChanged(): bool
    {
        return false;
    }

    public function value(): string
    {
        return 'cancelled';
    }

    public function humanValue(): string
    {
        return 'Отменен';
    }
}