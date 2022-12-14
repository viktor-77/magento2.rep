<?php

namespace Tsg\BestPrice\Service;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class ProductPagination
{
    private CollectionFactory $_productFactory;

    /**
     * @param CollectionFactory $productFactory
     */
    public function __construct(
        CollectionFactory $productFactory
    ) {
        $this->_productFactory = $productFactory;
    }

    /**
     * @param int $page
     * @return array
     */
    public function getProcessedProducts(int $page): array
    {
        $productCollection = $this->_productFactory->create();
        $productCollection->getSelect()->limitPage($page, 10);
        $productCollection->addAttributeToSelect('price');

        $processedProducts = [];
        foreach ($productCollection as $product) {
            $processedProducts[] =
                [
                    'product_id' => $product->getEntityId(),
                    'price' => (int)$product->getPrice(),
                    'name' => $product->getSku(),
                ];
        }
        return $processedProducts;
    }
}
