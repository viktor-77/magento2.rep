<?php

namespace Tsg\Sales\Controller\Adminhtml\Order;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Details extends \Magento\Backend\App\Action
{
    private const PAGE_TITLE = 'BestPrice details';

    private PageFactory $pageFactory;
    private Context $context;

    /**
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context     $context,
        PageFactory $pageFactory
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
    }

    /**
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(self::PAGE_TITLE);
        return $resultPage;
    }
}
