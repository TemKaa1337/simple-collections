<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Processor\Sort;

use Temkaa\SimpleCollections\Enum\SortOrder;
use Temkaa\SimpleCollections\Model\Sort\ByKeys;
use Temkaa\SimpleCollections\Model\SortCriteriaInterface;

/**
 * @internal
 */
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
