<?php

namespace Tsg\BestPrice\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Tsg\BestPrice\Service\AdminData\Config;

//use Magento\Framework\Data\Form\FormKey\Validator;
//save form data
class SaveConfig extends Action
{
    private Config $adminData;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Config $adminData
     */
    public function __construct(
        Context     $context,
        PageFactory $resultPageFactory,
        Config      $adminData
    )
    {
        $this->adminData = $adminData;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $bestPriceProductAttributes = $this->getRequest()->getPostValue();
        $this->adminData->setData($bestPriceProductAttributes);

        return $this->resultRedirectFactory->create()->setPath('best_price/product/index');
    }
}
