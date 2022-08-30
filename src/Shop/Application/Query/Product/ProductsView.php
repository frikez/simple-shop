<?php
declare(strict_types=1);

namespace App\Shop\Application\Query\Product;

class ProductsView
{
    public function __construct(private array $products, private int $count) {

    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return mixed
     */
    public function getCount(): int
    {
        return $this->count;
    }



}
