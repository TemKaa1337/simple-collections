<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Model\Sort;

use Temkaa\SimpleCollections\Enum\SortOrder;
use Temkaa\SimpleCollections\Model\SortCriteriaInterface;

final readonly class ByValues implements SortCriteriaInterface
{
    public function __construct(
        public SortOrder $order = SortOrder::Asc,
        public int $flags = SORT_REGULAR,
    ) {
    }
}
