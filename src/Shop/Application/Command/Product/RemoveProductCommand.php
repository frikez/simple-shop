<?php
declare(strict_types=1);

namespace App\Shop\Application\Command\Product;

use App\Shop\Application\Command\MessageInterface;
use Symfony\Component\Uid\AbstractUid;

class RemoveProductCommand implements MessageInterface
{
    public function __construct(private AbstractUid $productId)   {}

    /**
     * @return AbstractUid
     */
    public function getProductId(): AbstractUid
    {
        return $this->productId;
    }
}
