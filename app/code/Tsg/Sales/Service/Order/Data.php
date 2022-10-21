<?php

namespace Tsg\Sales\Service\Order;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Data
{
    private const SCOPE_PATH = 'order_timelines/general/live_time';

    private ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return int
     */
    public function getDelayTime(): int
    {
        return $this->scopeConfig->getValue(
            self::SCOPE_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }
}
