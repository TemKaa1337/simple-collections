<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Processor\Unique;

use Temkaa\SimpleCollections\Model\UniqueCriteriaInterface;

/**
 * @internal
 */
final class DefaultProcessor implements ProcessorInterface
{
    public function process(array $elements, ?UniqueCriteriaInterface $criteria): array
    {
        return array_values(array_unique($elements, SORT_REGULAR));
    }

    public function supports(?UniqueCriteriaInterface $criteria): bool
    {
        return $criteria === null;
    }
}
