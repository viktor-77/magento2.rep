<?php

namespace Tsg\Sales\Api\Order;

interface RepositoryInterface
{
    /**
     * @param $order
     * @return mixed
     */
    public function save($order);
}
