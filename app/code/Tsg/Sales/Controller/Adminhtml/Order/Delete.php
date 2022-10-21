<?php

namespace Tsg\Sales\Controller\Adminhtml\Order;

use  Magento\Backend\App\Action\Context;
use  Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use  Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use  Tsg\Sales\Service\Order\DeleteOrder;

class Delete extends \Magento\Backend\App\Action
{
    private RequestInterface $request;
    private Context $context;
    protected $resultFactory;
    private DeleteOrder $orderDelete;

    /**
     * @param Context $context
     * @param RequestInterface $request
     * @param ResultFactory $resultFactory
     * @param DeleteOrder $orderDelete
     */
    public function __construct(
        Context          $context,
        RequestInterface $request,
        ResultFactory    $resultFactory,
        DeleteOrder      $orderDelete
    ) {
        $this->request = $request;
        $this->resultFactory = $resultFactory;
        $this->orderDelete = $orderDelete;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $this->orderDelete->delete($this->request->getParam('selected') ?? []);
        return $this->resultRedirectFactory->create()->setPath('sales/order/details');
    }
}
