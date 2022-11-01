<?php

namespace Tsg\BestPrice\Controller\TestJson;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Tsg\BestPrice\Service\ProductPagination;

class TestJsonController extends Action
{
    private JsonFactory $_resultJsonFactory;
    private ProductPagination $productPagination;

    /**
     * @param JsonFactory $resultJsonFactory
     * @param Context $context
     * @param RequestInterface $request
     * @param ProductPagination $productPagination
     */
    public function __construct(
        JsonFactory       $resultJsonFactory,
        Context           $context,
        RequestInterface  $request,
        ProductPagination $productPagination
    ) {
        parent::__construct($context);
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_request = $request;
        $this->productPagination = $productPagination;
    }

    /**
     * @return ResponseInterface|Json|ResultInterface
     */
    public function execute()
    {
        return $this->_resultJsonFactory->create()->setData(
            $this->productPagination->getProcessedProducts($this->_request->getParam('page') ?: 1)
        );
    }
}
