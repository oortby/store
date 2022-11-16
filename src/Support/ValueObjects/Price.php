<?php

declare(strict_types=1);

namespace Support\ValueObjects;

use http\Exception\InvalidArgumentException;
use Support\Traits\Makeable;

final class Price
{
    use Makeable;

    private array $currencies = [
        'RUB' => '₽'
    ];

    public function __construct(
        private readonly int $value,
        private readonly string $currency = 'RUB',
        private readonly int $precision = 100,

    ) {
        if($value < 0 ){
            throw new InvalidArgumentException('Price must be more than zero');
        }

        if(!isset($this->currencies[$currency])){
            throw new InvalidArgumentException('Currency not allowed');
        }
    }

}