<?php

declare(strict_types=1);

namespace Domain\Order\Payment;

use Illuminate\Support\Collection;
use Support\ValueObjects\Price;

final class PaymentData
{
    public $id;

    public function __construct(
//        public string $id,
//        public string $description,
//        public string $returnUrl,
//        public Price $amount,
//        public Collection $meta,
    ) {}
}