<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Processor\Sort;

use Temkaa\SimpleCollections\Enum\SortOrder;
use Temkaa\SimpleCollections\Model\Sort\ByValues;
use Temkaa\SimpleCollections\Model\SortCriteriaInterface;

/**
 * @internal
 */
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
