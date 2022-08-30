<?php
declare(strict_types=1);

namespace App\Shop\Application\Query\Product;

use App\Shop\Application\Query\QueryHandlerInterface;
use App\Shop\Domain\Repository\ProductRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetProductQueryHandler implements MessageHandlerInterface, QueryHandlerInterface
{

    public function __construct(private ProductRepositoryInterface $repository)
    {

    }

    public function __invoke(GetProductQuery $query): ProductView
    {
        return $this->repository->get($query->getProductId());
    }
}
