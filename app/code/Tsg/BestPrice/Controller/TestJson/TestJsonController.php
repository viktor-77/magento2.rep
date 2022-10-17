<?php

namespace Tsg\BestPrice\Controller\TestJson;

use Exception;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Action\Action;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\View\Result\PageFactory;
use Tsg\BestPrice\Model\Repository;
use Tsg\BestPrice\Service\AdminData\Config;

class TestJsonController extends Action
{
    protected PageFactory $_pageFactory;

    private Config $adminConfig;
    private CollectionFactory $_productCollectionFactory;
    private Repository $repository;
    private ResourceConnection $connection;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param Config $adminConfig
     * @param CollectionFactory $productCollectionFactory
     * @param Repository $repository
     * @param ResourceConnection $connection
     */
    public function __construct(
        Context            $context,
        PageFactory        $pageFactory,
        Config             $adminConfig,
        CollectionFactory  $productCollectionFactory,
        Repository         $repository,
        ResourceConnection $connection
    )
    {
        $this->_pageFactory = $pageFactory;

        $this->adminConfig = $adminConfig;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->repository = $repository;
        $this->connection = $connection;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     * @throws CouldNotSaveException
     */
    public function execute()
    {
        $connection = $this->connection->getConnection();
        $connection->beginTransaction();
        try {
            $connection->delete('best_price_products');
            $this->repository->save($this->prepareProductsForSave());
            $connection->commit();
        } catch (Exception $e) {
            $connection->rollBack();
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        return $this->_pageFactory->create();
    }

    /**
     * @return array
     */
    private function getSortedProductCollection(): array
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
        foreach ($productCollection as $product) {
            $result[$product->getId()] = $product->getPrice();
        }

        return $result;
    }

    /**
     * @return array
     */
    private function prepareProductsForSave(): array
    {
        $result = [];
        foreach ($this->getSortedProductCollection() as $productId => $productPrice) {
            $result[] = [
                'product_id' => $productId,
                'price' => $productPrice,
            ];
        }
        return $result;
    }
}
