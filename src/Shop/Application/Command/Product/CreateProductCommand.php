<?php
declare(strict_types=1);

namespace App\Shop\Application\Command\Product;

use App\Shop\Application\Command\MessageInterface;
use App\Shop\Domain\ValueObject\Money;
use Symfony\Component\Validator\Constraints as Assert;

class CreateProductCommand implements MessageInterface
{
    #[Assert\NotBlank]
    private string $name;
    #[Assert\NotBlank]
    #[Assert\Length(min: 10)]
    private string $description;
    #[Assert\NotBlank]
    private Money $price;

    public function __construct(
        string $name,
        string $description,
        Money $price,
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
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

}
