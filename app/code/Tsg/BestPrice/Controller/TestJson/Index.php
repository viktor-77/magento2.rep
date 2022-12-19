<?php

namespace Tsg\BestPrice\Controller\TestJson;

use Exception;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\CouldNotSaveException;
use Tsg\BestPrice\Model\Repository;
use Tsg\BestPrice\Service\AdminData\Config;

class Index extends Action
{
    public function __construct(
        Context            $context,
        RequestInterface   $request,
        Config             $adminConfig,
        CollectionFactory  $_productCollectionFactory,
        Repository         $repository,
        ResourceConnection $resourceConnection
    )
    {
        parent::__construct($context);
        $this->_request = $request;
        $this->adminConfig = $adminConfig;
        $this->_productCollectionFactory = $_productCollectionFactory;
        $this->repository = $repository;
        $this->resourceConnection = $resourceConnection;
    }

    public function execute()
    {
        $connection = $this->resourceConnection->getConnection();
        $connection->beginTransaction();

        try {
            $connection->delete('best_price_products');
            $this->repository->save($this->getProductsFilteredByPrice());
            $connection->commit();
        } catch (Exception $e) {
            $connection->rollBack();
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        return $this->resultRedirectFactory->create()->setPath('best_price/testjson');
    }

    private function getProductsFilteredByPrice(): array
    {
        $adminData = $this->adminConfig->getData();
        $minPrice = $adminData['min_price'];
        $maxPrice = $adminData['max_price'];

        $productCollection = $this->_productCollectionFactory->create();
        $productCollection->addAttributeToFilter('price', ['gteq' => $minPrice]);
        $productCollection->addAttributeToFilter('price', ['lteq' => $maxPrice]);
        $productCollection->getSelect()
            ->order('at_price.value')
            ->limit(100);

        $result = [];
        $x = '';
        foreach ($productCollection as $product) {
            $result[] = ['product_id' => $product->getId()];
            $x = $product;
        }
        return $result;
    }
}
