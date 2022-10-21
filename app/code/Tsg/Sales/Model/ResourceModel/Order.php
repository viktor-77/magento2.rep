<?php

namespace Tsg\Sales\Model\ResourceModel;

class Order extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('order_short_details', 'id');
    }
}
