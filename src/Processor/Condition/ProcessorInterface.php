<?php

declare(strict_types=1);

namespace SimpleCollections\Processor\Condition;

use SimpleCollections\Model\ConditionInterface;

interface ProcessorInterface
{
    public function process(array $elements, ConditionInterface $condition): array;

    public function supports(ConditionInterface $condition): bool;
}
