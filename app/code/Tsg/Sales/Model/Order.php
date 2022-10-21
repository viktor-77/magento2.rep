<?php

namespace Tsg\Sales\Model;

class Order extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    private const TABLE_NAME = 'order_short_details';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Tsg\Sales\Model\ResourceModel\Order::class);
    }

    /**
     * @return string[]
     */
    public function getIdentities(): array
    {
        return [self::TABLE_NAME . '_' . $this->getId()];
    }
}
