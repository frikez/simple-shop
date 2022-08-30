<?php


namespace App\Shop\Application\Query\Product;


use Symfony\Component\Uid\AbstractUid;

final class GetProductQuery
{
    public function __construct(private AbstractUid $productId)
    {}

    public function getProductId(): AbstractUid
    {
        return $this->productId;
    }
}
