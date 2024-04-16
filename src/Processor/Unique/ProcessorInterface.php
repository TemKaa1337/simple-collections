<?php

declare(strict_types=1);

namespace SimpleCollections\Processor\Unique;

use SimpleCollections\Model\UniqueCriteriaInterface;

interface ProcessorInterface
{
    public function process(array $elements, ?UniqueCriteriaInterface $criteria): array;

    public function supports(?UniqueCriteriaInterface $criteria): bool;
}
