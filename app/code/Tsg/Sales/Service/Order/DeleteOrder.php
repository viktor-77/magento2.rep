<?php

namespace Tsg\Sales\Service\Order;

use Magento\Framework\App\ResourceConnection;

class DeleteOrder
{
    private const TABLE_NAME = 'order_short_details';

    private ResourceConnection $connection;

    /**
     * @param ResourceConnection $connection
     */
    public function __construct(ResourceConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param array $ordersId
     * @return void
     */
    public function delete(array $ordersId): void
    {
        $connection = $this->connection->getConnection();
        $tableName = $connection->getTableName(self::TABLE_NAME);
        if (empty($ordersId)) {
            $connection->delete($tableName);
        } else {
            $connection->delete($tableName, ['id IN (?)' => $ordersId]);
        }
    }
}
