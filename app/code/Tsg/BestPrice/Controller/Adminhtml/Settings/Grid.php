<?php

namespace Tsg\BestPrice\Controller\Adminhtml\Settings;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\View\Result\PageFactory;
use  Tsg\BestPrice\Model\Repository;

class Grid extends Action
{
    private const TABLE_NAME = 'best_price_products';

    private ResourceConnection $resourceConnection;
    protected RequestInterface $request;
    private CollectionFactory $_productCollectionFactory;
    private Repository $repository;
    private PageFactory $resultPageFactory;

    /**
     * @param PageFactory $resultPageFactory
     * @param Context $context
     * @param Repository $repository
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        PageFactory        $resultPageFactory,
        Context            $context,
        Repository         $repository,
        CollectionFactory  $collectionFactory,
        RequestInterface   $request,
        ResourceConnection $resourceConnection
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->repository = $repository;
        $this->_productCollectionFactory = $collectionFactory;
        $this->request = $request;
        $this->resourceConnection = $resourceConnection;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     * @throws CouldNotSaveException
     */
    public function execute()
    {
        $this->saveList($this->getIdsList($this->request->getParam('selected')));

        return $this->resultRedirectFactory->create()->setPath('best_price/settings/display');
    }

    /**
     * @param $idsList
     * @return void
     * @throws CouldNotSaveException
     */
    private function saveList($idsList): void
    {
        $connection = $this->resourceConnection->getConnection();
        $connection->beginTransaction();
        try {
            $connection->delete(self::TABLE_NAME);
            $this->repository->save($idsList);
            $connection->commit();
        } catch (Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
    }

    /**
     * @param $requestIds
     * @return array
     */
    private function getIdsList($requestIds): array
    {
        $idsList = [];
        if ($requestIds) {
            foreach ($requestIds as $id) {
                $idsList[] = ['product_id' => $id];
            }
        } else {
            $productList = $this->_productCollectionFactory->create()->getData();
            foreach ($productList as $product) {
                $idsList[] = ['product_id' => $product['entity_id']];
            }
        }

        return $idsList;
    }
}
