<?php

namespace Tsg\BestPrice\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class BestPrice extends AbstractModel implements IdentityInterface
{
    private const TABLE_NAME = 'catalog_product_entity';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\BestPrice::class);
    }

    /**
     * @return string[]
     */
    public function getIdentities(): array
    {
        return [self::TABLE_NAME . '_' . $this->getId()];
    }
}
