<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Model\Sort;

use Closure;
use Temkaa\SimpleCollections\Model\SortCriteriaInterface;

final readonly class ByCallback implements SortCriteriaInterface
{
    public function __construct(
        public Closure $callback,
        public bool $sortValues = true,
    ) {
    }
}
