<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Processor\Condition;

use Temkaa\SimpleCollections\Model\ConditionInterface;

interface ProcessorInterface
{
    public function process(array $elements, ConditionInterface $condition): array;

    public function supports(ConditionInterface $condition): bool;
}
