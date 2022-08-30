<?php
declare(strict_types=1);

namespace App\Shop\Application\Command\Product;

use App\Shop\Application\Command\CommandHandlerInterface;
use App\Shop\Application\Product\Event\ProductWasCreatedEvent;
use App\Shop\Domain\Model\Product;
use App\Shop\Domain\Repository\ProductRepositoryInterface;
use App\Shop\Domain\ValueObject\Currency;
use App\Shop\Domain\ValueObject\Money;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class CreateProductCommandHandler implements MessageHandlerInterface, CommandHandlerInterface
{

    public function __construct(
        private MessageBusInterface $eventBus,
        private ProductRepositoryInterface $productRepository
    ) {}

    public function __invoke(CreateProductCommand $command): AbstractUid
    {
        $product = Product::create(
            Uuid::v4(),
            $command->getName(),
            $command->getDescription(),
            $command->getPrice()
        );

        $this->productRepository->add($product);
        $this->eventBus->dispatch(new ProductWasCreatedEvent($product));
        return $product->getId();
    }
}
