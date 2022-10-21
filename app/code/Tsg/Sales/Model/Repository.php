<?php

namespace Tsg\Sales\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use Tsg\Sales\Api\Order\RepositoryInterface;
use Tsg\Sales\Model\ResourceModel\Order as OrderResource;

class Repository implements RepositoryInterface
{
    private OrderResource $orderResource;

    /**
     * @param OrderResource $orderResource
     */
    public function __construct(
        OrderResource $orderResource
    ) {
        $this->orderResource = $orderResource;
    }

    /**
     * @param $order
     * @return mixed|void
     * @throws CouldNotSaveException
     */
    public function save($order)
    {
        try {
            $this->orderResource->save($order);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
    }
}
