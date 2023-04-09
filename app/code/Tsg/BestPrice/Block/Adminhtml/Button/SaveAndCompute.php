<?php

namespace Tsg\BestPrice\Block\Adminhtml\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveAndCompute extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save and compute'),
            'on_click' => sprintf("location.href = '%s';", 'best_price/product/saveconfig'),
            'class' => 'primary',
            'sort_order' => 10
        ];
    }
}
