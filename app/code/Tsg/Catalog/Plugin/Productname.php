<?php

namespace Tsg\Catalog\Plugin;

use Magento\Catalog\Model\Product;

class Productname
{
    /**
     * @param Product $subject
     * @param string $result
     * @return string
     */
    public function afterGetName(Product $subject, string $result): string
    {
        return $result . ' (' . $subject->getTypeId() . ')';
    }
}
