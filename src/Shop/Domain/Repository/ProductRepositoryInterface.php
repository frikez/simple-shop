<?php
namespace App\Shop\Domain\Repository;


use App\Shop\Application\Query\Product\ProductView;
use App\Shop\Domain\Model\Product;
use Symfony\Component\Uid\AbstractUid;

interface ProductRepositoryInterface {

    public function get(AbstractUid $id): ProductView;

    public function add(Product $product): void;

    public function remove(Product $product): void;
}
