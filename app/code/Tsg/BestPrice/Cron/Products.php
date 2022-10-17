<?php

namespace Tsg\BestPrice\Cron;

use Exception;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\CouldNotSaveException;
use Tsg\BestPrice\Model\BestPriceFactory;
use Tsg\BestPrice\Model\Repository;
use Tsg\BestPrice\Service\AdminData\Config;

class Products
{
    private const BEST_PRICE_PRODUCT_QUANTITY = 100;

    private Config $adminConfig;
    private CollectionFactory $_productCollectionFactory;
    protected BestPriceFactory $_bestPriceFactory;
    private Repository $repository;
    private ResourceConnection $connection;

    /**
     * @param Config $adminConfig
     * @param CollectionFactory $productCollectionFactory
     * @param BestPriceFactory $bestPriceFactory
     * @param Repository $repository
     * @param ResourceConnection $connection
     */
    public function __construct(
        Config             $adminConfig,
        CollectionFactory  $productCollectionFactory,
        BestPriceFactory   $bestPriceFactory,
        Repository         $repository,
        ResourceConnection $connection
    ) {
        $this->adminConfig = $adminConfig;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_bestPriceFactory = $bestPriceFactory;
        $this->repository = $repository;
        $this->connection = $connection;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws CouldNotSaveException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        $this->connection->getConnection()->delete('best_price_products');

        foreach ($this->getSortedProductCollection() as $product) {
            $bestPriceModel = $this->_bestPriceFactory->create();
            $bestPriceModel->addData([
                'product_id' => (int)$product['id'],
                'price' => (int)$product['price'],
            ]);

            try {
                $this->repository->save($bestPriceModel);
            } catch (Exception $e) {
                throw new CouldNotSaveException(__($e->getMessage()));
            }
        }

        return $exitCode;
    }

    /**
     * @return array
     */
    private function getSortedProductCollection(): array
    {
        $adminData = $this->adminConfig->getData();
        $minPrice = $adminData['min_price'];
        $maxPrice = $adminData['max_price'];

        $productCollection = $this->_productCollectionFactory->create();
        $productCollection->addAttributeToSelect('price');

        $result = [];
        foreach ($productCollection as $product) {
            $productPrice = $product->getPrice();
            if ($productPrice >= $minPrice && $productPrice <= $maxPrice) {
                $result[] = [
                    'id' => $product->getId(),
                    'price' => $product->getPrice()
                ];
            }
        }
        usort($result, static fn ($a, $b) => $b['price'] <=> $a['price']);

        return array_slice($result, 0, self::BEST_PRICE_PRODUCT_QUANTITY);
    }
}
