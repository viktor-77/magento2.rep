<?php

namespace Tsg\BestPrice\Model\RadioConfigField;

use Magento\Framework\Option\ArrayInterface;

class WorkMods implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $options = [
            0 => [
                'label' => 'Auto',
                'value' => 'auto'
            ],
            1 => [
                'label' => 'Hand',
                'value' => 'hand'
            ],
        ];

        return $options;
    }
}
