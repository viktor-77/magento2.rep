<?php

namespace Tsg\Sales\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use Tsg\Sales\Api\Order\RepositoryInterface;
use Tsg\Sales\Model\ResourceModel\Order as OrderResourceModel;

class Repository implements RepositoryInterface
{
    private OrderResourceModel $orderResource;

    /**
     * @param OrderResourceModel $orderResource
     */
    public function __construct(
        OrderResourceModel $orderResource
    )
    {
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
