<?php
declare(strict_types=1);

namespace App\Shop\Infrastructure\Serializer\Normalizer;

use App\Shop\Domain\ValueObject\Currency;
use App\Shop\Domain\ValueObject\Money;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CurrencyNormalizer  implements NormalizerInterface
{

    public function normalize($money, string $format = null, array $context = [])
    {
        $data['currency'] = $money->getCurrency()->getSymbol();
        $data['amount'] = $money->getAmount();

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof Money;
    }
}
