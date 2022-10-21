<?php

namespace Tsg\BestPrice\Controller\Adminhtml\Settings;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\PageFactory;
use  Tsg\BestPrice\Model\Repository;

class SaveGrid extends Action
{
    public function __construct(
        PageFactory       $resultPageFactory,
        Context           $context,
        Repository        $repository,
        CollectionFactory $collectionFactory,
        RequestInterface  $request
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->repository = $repository;
        $this->_productCollectionFactory = $collectionFactory;
        $this->request = $request;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->res = $this->request->getParam('selected') ?? [];
        return $this->resultPageFactory->create();
    }
}
