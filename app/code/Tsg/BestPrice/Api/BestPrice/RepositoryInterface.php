<?php

namespace Tsg\BestPrice\Api\BestPrice;

interface RepositoryInterface
{
    /**
     * @param array $bestPriceProducts
     * @return void
     */
    public function save(array $bestPriceProducts): void;
}
