<?php

namespace Tsg\BestPrice\Api\BestPrice;

interface RepositoryInterface
{
    /**
     * @param array $products
     * @return void
     */
    public function save(array $products): void;
}
