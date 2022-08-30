<?php
declare(strict_types=1);

namespace App\Shop\Application\Query\Product;

use App\Shop\Application\Query\QueryHandlerInterface;
use App\Shop\Domain\Repository\ProductRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetProductsQueryHandler implements MessageHandlerInterface, QueryHandlerInterface
{
    public function __construct(
        private ProductRepositoryInterface $repository
    )
    {

    }
    public function __invoke(GetProductsQuery $query): ProductsView
    {
        $products = $this->repository->slice(
            $query->getLimit(),
            $query->getOffset(),
            $query->getOrderBy(),
            $query->getOrderDirection(),
        );
        $count =$this->repository->count();

        return new ProductsView($products, $count);
    }


}
