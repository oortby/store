<?php

declare(strict_types=1);

namespace Domain\Order\Actions\Contracts;


use Domain\Order\DTOs\NewOrderDTO;

interface NewOrderContract
{
    public function __invoke(NewOrderDTO $data);

}