<?php
declare(strict_types=1);

namespace App\Shop\Application\Command\Product;

use App\Shop\Application\Command\CommandHandlerInterface;
use App\Shop\Domain\Model\Product;
use App\Shop\Domain\Repository\ProductRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;


class RemoveProductCommandHandler implements MessageHandlerInterface, CommandHandlerInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(RemoveProductCommand $command): void
    {
        $product = $this->entityManager->getReference(Product::class, $command->getProductId());
        $this->productRepository->remove($product);
    }
}
