<?php

namespace Tsg\Sales\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Tsg\Sales\Model\OrderFactory;
use Tsg\Sales\Model\Repository;

class OrderObserver implements ObserverInterface
{
    private OrderFactory $orderFactory;
    private Repository $orderRepository;

    /**
     * @param OrderFactory $orderFactory
     * @param Repository $orderRepository
     */
    public function __construct(
        OrderFactory $orderFactory,
        Repository   $orderRepository
    ) {
        $this->orderFactory = $orderFactory;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws CouldNotSaveException
     */
    public function execute(Observer $observer)
    {
        $orderModel = $this->orderFactory->create();
        $order = $observer->getEvent()->getOrder();
        $orderModel->addData([
         'order_id' => (int)$order->getId(),
         'total_price' => (int)$order->getGrandTotal(),
         'items_ordered' => (int)$order->getTotalQtyOrdered(),
         'created_at' => $order->getCreatedAt()
      ]);
        try {
            $this->orderRepository->save($orderModel);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
    }
}
