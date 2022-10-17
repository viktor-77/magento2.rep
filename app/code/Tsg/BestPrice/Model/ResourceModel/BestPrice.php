<?php

namespace Tsg\BestPrice\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class BestPrice extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('best_price_products', 'id');
    }
}
