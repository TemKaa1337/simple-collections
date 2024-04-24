<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Processor\Sort;

use Temkaa\SimpleCollections\Model\Sort\ByCallback;
use Temkaa\SimpleCollections\Model\SortCriteriaInterface;

final class ByCallbackProcessor implements ProcessorInterface
{
    /**
     * @param ByCallback $criteria
     */
    public function process(array $elements, SortCriteriaInterface $criteria): array
    {
        $isArray = array_is_list($elements);

        $sortFunction = match (true) {
            $isArray                            => usort(...),
            !$isArray && $criteria->sortValues  => uasort(...),
            !$isArray && !$criteria->sortValues => uksort(...),
        };

        $sortFunction($elements, $criteria->callback);

        return $elements;
    }

    public function supports(SortCriteriaInterface $criteria): bool
    {
        return $criteria instanceof ByCallback;
    }
}
