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
    private array $configMap = [
        'is_enabled' => 'admin_form/general/is_enabled',
        'block_name' => 'admin_form/general/block_name',
        'min_price' => 'admin_form/general/min_price',
        'max_price' => 'admin_form/general/max_price',
        'work_mode' => 'admin_form/general/work_mode',
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
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $result = [];
        foreach ($this->configMap as $configField => $configPath) {
            $result[$configField] = $this->scopeConfig->getValue(
                $configPath,
                ScopeInterface::SCOPE_STORE
            );
        }
        return $result;
    }

    /**
     * @param array $configValue
     * @return void
     */
    public function setData(array $configValue): void
    {
        foreach ($this->configMap as $configField => $configPath) {
            $this->configWriter->save($configPath, $configValue[$configField]);
        }

        $this->cacheTypeList->cleanType('config');
    }
}
