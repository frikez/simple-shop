<?php

namespace App\Shop\Infrastructure\Repository;


use App\Shop\Application\Query\Product\ProductView;
use App\Shop\Domain\Exception\ResourceNotFoundException;
use App\Shop\Domain\Model\Product;
use App\Shop\Domain\Repository\ProductRepositoryInterface;
use App\Shop\Domain\ValueObject\Currency;
use App\Shop\Domain\ValueObject\Money;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\AbstractUid;

class DQLProductRepository implements ProductRepositoryInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @throws ResourceNotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function get(AbstractUid $id): ProductView
    {
        $repository = $this->em->getRepository(Product::class);
        $productData = $repository
            ->createQueryBuilder('product')
            ->andWhere('product.id = :id')
            ->setParameter('id', $id, 'uuid')
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

        if (null === $productData) {
            throw new ResourceNotFoundException($id);
        }

        return new ProductView(
            $productData['id'],
            $productData['name'],
            $productData['description'],
            new Money($productData['price.amount'], new Currency($productData['price.currency.symbol']))
        );
    }

    public function add(Product $product): void
    {
        $this->em->persist($product);
        $this->em->flush();
    }

    public function remove(Product $product): void
    {
        $this->em->remove($product);
        $this->em->flush();
    }

    public function slice(int $limit, int $offset, string $orderBy, string $orderDirection): array
    {
        $query = $this->em->getRepository(Product::class)
            ->createQueryBuilder('product')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy(sprintf('product.%s', $orderBy), $orderDirection)
            ->getQuery();

        $productsData = $query->getResult(AbstractQuery::HYDRATE_ARRAY);

        return array_map(function(array $productData) {
            return new ProductView(
                $productData['id'],
                $productData['name'],
                $productData['description'],
                new Money($productData['price.amount'], new Currency($productData['price.currency.symbol']))
            );
        }, $productsData);
    }

    public function count(): int
    {
        return $this->em
            ->createQueryBuilder()
            ->select('count(product)')
            ->from(Product::class, 'product')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
