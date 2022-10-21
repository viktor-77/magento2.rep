<?php

namespace Tsg\Sales\Console\Order;

use Magento\Framework\App\ResourceConnection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tsg\Sales\Service\Order\Data;

class CleanDetails extends Command
{
    private const COMMAND_SYNTAX = 'order:clean';
    private const TABLE_NAME = 'order_short_details';
    private const CLEAN_SUCCESS_MESSAGE = "Data cleaned";
    private const CLEAN_FAILED_MESSAGE = "Nothing to clean";
    private const COMMAND_DESCRIPTION = 'Clean short order details';

    private Data $orderData;
    private ResourceConnection $connection;

    /**
     * @param Data $orderData
     * @param ResourceConnection $connection
     */
    public function __construct(
        Data               $orderData,
        ResourceConnection $connection
    ) {
        $this->orderData = $orderData;
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
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        $delayTime = $this->orderData->getDelayTime();
        $deletedRecords = $this->deleteRecords($delayTime);
        $this->resultMessage($deletedRecords, $output);
        return $exitCode;
    }

    /**
     * @param int $delayTime
     * @return int
     */
    private function deleteRecords(int $delayTime): int
    {
        $connection = $this->connection->getConnection();
        $tableName = $connection->getTableName(self::TABLE_NAME);
        $whereCondition = [
            'created_at < ?' => date("Y-m-d H:i:s", strtotime("-$delayTime day"))
        ];
        return $connection->delete($tableName, $whereCondition);
    }

    /**
     * @param int $deletedRecords
     * @param OutputInterface $output
     * @return void
     */
    private function resultMessage(int $deletedRecords, OutputInterface $output): void
    {
        if ($deletedRecords !== 0) {
            $output->writeln(self::CLEAN_SUCCESS_MESSAGE);
        } else {
            $output->writeln(self::CLEAN_FAILED_MESSAGE);
        }
    }
}
