<?php
declare(strict_types=1);

namespace App\Shop\Application\Product\Event;

use App\Shop\Domain\Model\Product;

class ProductWasCreatedEvent
{
    public function __construct(
        private Product $product
    )   {}
}
