<?php

declare(strict_types=1);

namespace SimpleCollections\Processor\Sum;

use SimpleCollections\Model\SumCriteriaInterface;

final class DefaultProcessor implements ProcessorInterface
{
    public function process(array $elements, ?SumCriteriaInterface $criteria): float|int
    {
        return array_sum($elements);
    }

    public function supports(?SumCriteriaInterface $criteria): bool
    {
        return $criteria === null;
    }
}
