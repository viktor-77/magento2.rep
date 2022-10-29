<?php

namespace Tsg\BestPrice\Controller\Adminhtml\Settings;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Controller\Result\Redirect;
use  Magento\Framework\Exception\CouldNotSaveException;

use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;
use Tsg\BestPrice\Model\Repository;

class Grid extends Action
{
    private const TABLE_NAME = 'best_price_products';

    private ResourceConnection $resourceConnection;
    protected RequestInterface $request;
    private CollectionFactory $_productCollectionFactory;
    private Repository $repository;
    protected PageFactory $_resultPageFactory;
    protected Filter $filter;

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
        ResourceConnection $resourceConnection,
        Filter             $filter
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->repository = $repository;
        $this->_productCollectionFactory = $collectionFactory;
        $this->request = $request;
        $this->resourceConnection = $resourceConnection;
        $this->filter = $filter;
        parent::__construct($context);
    }

    /**
     * @return Redirect
     * @throws CouldNotSaveException
     */
    public function execute(): Redirect
    {
        $this->saveList($this->getIdsList());                    ///////////////////////

        return $this->resultRedirectFactory->create()->setPath('best_price/settings/display');
    }

    /**
     * @param array $idsList
     * @return void
     * @throws CouldNotSaveException
     */
    private function saveList(array $idsList): void
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
     * @return array
     */
    private function getIdsList(): array
    {
        $idsList = [];
        $selectedItems = $this->request->getParam('selected');
        $excludedItems = ($this->request->getParam('excluded') === "false") ? false : $this->request->getParam('excluded');
        $productList = $this->_productCollectionFactory->create()->getData();
        if ($selectedItems) {
            foreach ($selectedItems as $id) {
                $idsList[] = ['product_id' => $id];
            }
        } elseif ($excludedItems) {
            foreach ($productList as $product) {
                if (!in_array($product['entity_id'], $excludedItems, true)) {
                    $idsList[] = ['product_id' => $product['entity_id']];
                }
            }
        } else {
            foreach ($productList as $product) {
                $idsList[] = ['product_id' => $product['entity_id']];
            }
        }

        return $idsList;
    }
}
