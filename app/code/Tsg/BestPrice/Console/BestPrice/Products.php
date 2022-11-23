<?php

namespace Tsg\BestPrice\Console\BestPrice;

use Exception;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\CouldNotSaveException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Tsg\BestPrice\Model\Repository;
use Tsg\BestPrice\Service\AdminData\Config;

class Products extends Command
{
    private const COMMAND_SYNTAX = 'compute:best_price_products';
    private const COMMAND_DESCRIPTION = 'Computes the first hundred products on the site for display in our block';
    private const BEST_PRICE_PRODUCT_QUANTITY = 100;
    private const TABLE_NAME = 'best_price_products';

    private Config $adminConfig;
    private CollectionFactory $_productCollectionFactory;
    private Repository $repository;
    private ResourceConnection $connection;

    /**
     * @param Config $adminConfig
     * @param CollectionFactory $productCollectionFactory
     * @param Repository $repository
     * @param ResourceConnection $connection
     */
    public function __construct(
        Config             $adminConfig,
        CollectionFactory  $productCollectionFactory,
        Repository         $repository,
        ResourceConnection $connection
    ) {
        $this->adminConfig = $adminConfig;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->repository = $repository;
        $this->connection = $connection;

        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(self::COMMAND_SYNTAX);
        $this->setDescription(self::COMMAND_DESCRIPTION);
        parent::configure();
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

        $connection = $this->connection->getConnection();
        $connection->beginTransaction();
        try {
            $connection->delete(self::TABLE_NAME);
            $this->repository->save($this->getSortedProductCollection());
            $connection->commit();
        } catch (Exception $e) {
            $connection->rollBack();
            throw new CouldNotSaveException(__($e->getMessage()));
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
        $productCollection->addAttributeToFilter('price', ['gteq' => $minPrice]);
        $productCollection->addAttributeToFilter('price', ['lteq' => $maxPrice]);
        $productCollection->getSelect()
            ->order('at_price.value')
            ->limit(self::BEST_PRICE_PRODUCT_QUANTITY);

        $result = [];
        foreach ($productCollection as $product) {
            $result[] = ['product_id' => $product->getId()];
        }

        return $result;
    }
}
