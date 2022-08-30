<?php
declare(strict_types=1);

namespace App\Shop\Application\Query\Product;

use App\Shop\Domain\ValueObject\Money;
use Symfony\Component\Uid\AbstractUid;

final class ProductView
{
    public function __construct(
        private AbstractUid $id,
        private string $name,
        private string $description,
        private Money $price
    )   {

    }
    /**
     * @return AbstractUid
     */
    public function getId(): AbstractUid
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return Money
     */
    public function getPrice(): Money
    {
        return $this->price;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price->toArray(),
        ];
    }

}
