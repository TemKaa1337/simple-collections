<?php

declare(strict_types=1);

namespace SimpleCollections\Model\Sort;

use Closure;
use SimpleCollections\Model\SortCriteriaInterface;

final readonly class ByCallback implements SortCriteriaInterface
{
    public function __construct(
        public Closure $callback,
        public bool $sortValues = true,
    ) {
    }
}
