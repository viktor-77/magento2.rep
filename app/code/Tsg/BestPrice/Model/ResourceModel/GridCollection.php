<?php

namespace Tsg\BestPrice\Model\ResourceModel;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Psr\Log\LoggerInterface as Logger;

class GridCollection extends SearchResult
{
    public function __construct(
        EntityFactory $entityFactory,
        Logger        $logger,
        FetchStrategy $fetchStrategy,
        EventManager  $eventManager,
        $mainTable,
        $resourceModel = null,
        $identifierName = null,
        $connectionName = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel, $identifierName, $connectionName);
    }

    protected function _initSelect()
    {
        $this->getSelect()
            ->joinLeft(
                'catalog_product_entity_decimal AS decimal',
                'main_table.entity_id = decimal.entity_id',
                'value AS price'
            )
            ->joinLeft(
                'best_price_products AS products',
                'main_table.entity_id = products.product_id',
                'product_id'
            )
            ->where('decimal.value != ?', null)
            ->order('price');
        parent::_initSelect();
    }
}
