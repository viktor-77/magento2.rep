<?php

namespace Tsg\BestPrice\Model;

use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Tsg\BestPrice\Api\BestPrice\RepositoryInterface;
use Magento\Framework\App\ResourceConnection;

class Repository implements RepositoryInterface
{
    private const TABLE_NAME = 'best_price_products';
    private ResourceConnection $connection;

    /**
     * @param ResourceConnection $connection
     */
    public function __construct(ResourceConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param array $products
     * @return void
     * @throws CouldNotSaveException
     */
    public function save(array $products): void
    {
        $connection = $this->connection->getConnection();

        try {
            $connection->insertMultiple(self::TABLE_NAME, $products);
        } catch (Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
    }
}
