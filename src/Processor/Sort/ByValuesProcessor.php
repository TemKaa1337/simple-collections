<?php

declare(strict_types=1);

namespace SimpleCollections\Processor\Sort;

use SimpleCollections\Enum\SortOrder;
use SimpleCollections\Model\Sort\ByValues;
use SimpleCollections\Model\SortCriteriaInterface;

final class ByValuesProcessor implements ProcessorInterface
{
    /**
     * @param ByValues $criteria
     */
    public function process(array $elements, SortCriteriaInterface $criteria): array
    {
        $isArray = array_is_list($elements);
        $isAscendingOrder = $criteria->order === SortOrder::Asc;

        $sortFunction = match (true) {
            $isArray && $isAscendingOrder   => sort(...),
            $isArray && !$isAscendingOrder  => rsort(...),
            !$isArray && $isAscendingOrder  => asort(...),
            !$isArray && !$isAscendingOrder => arsort(...),
        };

        $sortFunction($elements, $criteria->flags);

        return $elements;
    }

    public function supports(SortCriteriaInterface $criteria): bool
    {
        return $criteria instanceof ByValues;
    }
}
