<?php
declare(strict_types=1);

namespace App\Shop\Domain\Model;

use App\Shop\Domain\ValueObject\Currency;
use App\Shop\Domain\ValueObject\Money;
use Symfony\Component\Uid\AbstractUid;


class Product
{
    private function __construct(
        private AbstractUid $id,
        private string $name,
        private string $description,
        private Money $price
    ) {

    }
    public static function create(AbstractUid $id, string $name, string $description, Money $price): self {
        if(empty($name)) {
            throw new \InvalidArgumentException('Product name cannot be empty');
        }

        if(strlen($description) < 10) {
            throw new \InvalidArgumentException('Product description cannot be shorter than 10 characters');
        }

        return new self($id,$name, $description, $price);
    }
    public function change(?string $name, ?string $description, ?Money $price) {
        if($name) {
            $this->name = $name;
        }

        if($description) {
            $this->description = $description;
        }

        if($price) {
            $this->price = $price;
        }
    }
    public static function createFromPrimitives($data): self {
        return new self($data['id'],$data['name'],$data['description'],new Money($data['price'], Currency::PLN()));
    }

    /**
     * @return AbstractUid
     */
    public function getId(): AbstractUid
    {
        return $this->id;
    }

    /**
     * @param AbstractUid $id
     * @return Product
     */
    public function setId(AbstractUid $id): Product
    {
        $this->id = $id;

        return $this;
    }


}
