<?php


namespace App\Shop\Application\Query\Product;


final class GetProductsQuery
{
    private array $supportedSortFields = ['name', 'price.amount'];
    public function __construct(
        private int $limit,
        private int $offset,
        private string $orderBy,
        private string $orderDirection,
    ) {
        $this->checkIsSupportedSortField();
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return string
     */
    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    /**
     * @return string
     */
    public function getOrderDirection(): string
    {
        return $this->orderDirection;
    }
    private function checkIsSupportedSortField() {
        if (!in_array($this->orderBy, $this->supportedSortFields)) {
            throw new \InvalidArgumentException(sprintf('Sort by field %s is not supported', $this->orderBy));
        }
    }

}
