<?php
declare(strict_types=1);

namespace App\Shop\Domain\ValueObject;

use InvalidArgumentException;

class Currency
{
    const SYMBOL_PLN = 'PLN';
    private array $supportedCurrencies = [
        self::SYMBOL_PLN
    ];

    public function __construct(private string $symbol)  {
        if (!in_array(mb_strtoupper($this->symbol), $this->supportedCurrencies)) {
            throw new InvalidArgumentException(sprintf("Illegal currency \"%s\".", $symbol));
        }
    }
    public static function PLN(): self {
        return new self(self::SYMBOL_PLN);
    }
    public function __toString()    {
        return $this->symbol;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }


}
