<?php

declare(strict_types=1);

namespace SimpleCollections\Processor\Sum;

use SimpleCollections\Model\SumCriteriaInterface;

interface ProcessorInterface
{
    public function process(array $elements, ?SumCriteriaInterface $criteria): float|int;

    public function supports(?SumCriteriaInterface $criteria): bool;
}