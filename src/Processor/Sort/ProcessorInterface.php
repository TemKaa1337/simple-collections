<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Processor\Sort;

use Temkaa\SimpleCollections\Model\SortCriteriaInterface;

interface ProcessorInterface
{
    public function process(array $elements, SortCriteriaInterface $criteria): array;

    public function supports(SortCriteriaInterface $criteria): bool;
}
