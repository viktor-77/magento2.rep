<?php

namespace Tsg\BestPrice\Model;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider as MagentoDataProvider;
use Tsg\BestPrice\Service\AdminData\Config;

class DataProvider extends MagentoDataProvider
{
    private Config $config;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        Config $config,
        array $meta = [],
        array $data = []
    ) {
        $this->config = $config;

        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            null => $this->config->getData()
        ];
    }
}
