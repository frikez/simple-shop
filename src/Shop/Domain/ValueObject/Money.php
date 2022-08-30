<?php
declare(strict_types=1);

namespace App\Shop\Domain\ValueObject;

use InvalidArgumentException;

class Money
{
    public function __construct(
        private float $amount,
        private Currency $currency
    )  {
        if ($this->amount <= 0) {
            throw new InvalidArgumentException("Money amount needs to be greater than zero.");
        }
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function toArray() {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency
        ];
    }
}
