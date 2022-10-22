<?php

namespace Tsg\BestPrice\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class IsAdded extends Column
{
    public function __construct(ContextInterface $context, UiComponentFactory $uiComponentFactory, array $components = [], array $data = [])
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource): array
    {
        $fieldName = 'product_id';
        foreach ($dataSource['data']['items'] as &$item) {
            if ($item[$fieldName]) {
                $item[$fieldName] = "Yes";
                $item ['checked'] = true;
            } else {
                $item[$fieldName] = "No";
            }
        }

        return parent::prepareDataSource($dataSource);
    }
}
