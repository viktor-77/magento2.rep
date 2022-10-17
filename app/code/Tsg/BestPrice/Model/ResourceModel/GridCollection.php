<?php

namespace Tsg\BestPrice\Model\ResourceModel;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class GridCollection extends SearchResult
{
    /**
     * Override _initSelect to add custom columns
     *
     * @return void
     */
    protected function _initSelect(): void
    {
        $this->getSelect()
            ->joinLeft(
                'catalog_product_entity_decimal',
                'main_table.product_id = catalog_product_entity_decimal.entity_id',
                'value AS price'
            )->joinLeft(
                'catalog_product_entity',
                'main_table.product_id = catalog_product_entity.entity_id',
                'sku'
            );

        parent::_initSelect();
    }
}
