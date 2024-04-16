<?php

declare(strict_types=1);

namespace SimpleCollections\Processor\Sort;

use SimpleCollections\Enum\SortOrder;
use SimpleCollections\Model\Sort\ByKeys;
use SimpleCollections\Model\SortCriteriaInterface;

final class ByKeysProcessor implements ProcessorInterface
{
    /**
     * @param ByKeys $criteria
     */
    public function process(array $elements, SortCriteriaInterface $criteria): array
    {
        $criteria->order === SortOrder::Asc
            ? ksort($elements, $criteria->flags)
            : krsort($elements, $criteria->flags);

        return $elements;
    }

    public function supports(SortCriteriaInterface $criteria): bool
    {
        return $criteria instanceof ByKeys;
    }
}
