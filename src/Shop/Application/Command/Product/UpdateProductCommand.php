<?php
declare(strict_types=1);

namespace App\Shop\Application\Command\Product;

use App\Shop\Application\Command\MessageInterface;
use App\Shop\Domain\ValueObject\Money;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateProductCommand implements MessageInterface
{
    #[Assert\NotBlank]
    private ?string $name;
    #[Assert\Length(min: 10)]
    private ?string $description;
    private ?Money $price;

    public function __construct(
        private AbstractUid $id,
        ?string $name,
        ?string $description,
        ?Money $price
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
    }

    /**
     * @return AbstractUid
     */
    public function getId(): AbstractUid
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return Money|null
     */
    public function getPrice(): ?Money
    {
        return $this->price;
    }

}
