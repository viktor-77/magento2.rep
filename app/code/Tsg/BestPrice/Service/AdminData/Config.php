<?php

namespace Tsg\BestPrice\Service\AdminData;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    private ScopeConfigInterface $scopeConfig;
    private WriterInterface $configWriter;
    private TypeListInterface $cacheTypeList;
    private array $bestPriceAttributesPath = [
        'is_enabled' => 'best_price_form/general/is_enabled',
        'block_name' => 'best_price_form/general/block_name',
        'min_price' => 'best_price_form/general/min_price',
        'max_price' => 'best_price_form/general/max_price',
        'work_mode' => 'best_price_form/general/work_mode',
    ];

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param WriterInterface $configWriter
     * @param TypeListInterface $cacheTypeList
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        WriterInterface      $configWriter,
        TypeListInterface    $cacheTypeList
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $productAttributes = [];
        foreach ($this->bestPriceAttributesPath as $attributeName => $attributeSystemConfigPath) {
            $productAttributes[$attributeName] = $this->scopeConfig->getValue(
                $attributeSystemConfigPath,
                ScopeInterface::SCOPE_STORE
            );
        }
        return $productAttributes;
    }

    /**
     * @param array $configValue
     * @return void
     */
    public function setData(array $productAttributes): void
    {
        foreach ($this->bestPriceAttributesPath as $attributeName => $attributeSystemConfigPath) {
            $this->configWriter->save($attributeSystemConfigPath, $productAttributes[$attributeName]);
        }

        $this->cacheTypeList->cleanType('config');
    }
}
