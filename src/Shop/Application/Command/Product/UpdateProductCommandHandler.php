<?php
declare(strict_types=1);

namespace App\Shop\Application\Command\Product;

use App\Shop\Application\Command\CommandHandlerInterface;
use App\Shop\Application\Product\Event\ProductWasCreatedEvent;
use App\Shop\Domain\Model\Product;
use App\Shop\Domain\Repository\ProductRepositoryInterface;
use App\Shop\Domain\ValueObject\Currency;
use App\Shop\Domain\ValueObject\Money;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\AbstractUid;

class UpdateProductCommandHandler implements MessageHandlerInterface, CommandHandlerInterface
{

    public function __construct(
        private MessageBusInterface $eventBus,
        private ProductRepositoryInterface $productRepository,
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke(UpdateProductCommand $command): AbstractUid
    {
        $product =  $this->entityManager->getReference(Product::class, $command->getId());
        $product->change($command->getName(), $command->getDescription(), $command->getPrice());
        $this->productRepository->add($product);
        return $product->getId();
    }
}
