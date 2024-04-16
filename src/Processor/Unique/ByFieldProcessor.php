<?php

declare(strict_types=1);

namespace SimpleCollections\Processor\Unique;

use SimpleCollections\Collection;
use SimpleCollections\Model\Unique\ByField;
use SimpleCollections\Model\UniqueCriteriaInterface;
use SimpleCollections\Trait\ValueRetrieverTrait;

final class ByFieldProcessor implements ProcessorInterface
{
    use ValueRetrieverTrait;

    /**
     * @param ByField $criteria
     */
    public function process(array $elements, ?UniqueCriteriaInterface $criteria): array
    {
        $duplicates = [];
        $valueRetriever = $this->retrieveCallable($criteria->field);

        $callback = function (mixed $value) use ($criteria, $valueRetriever, &$duplicates): bool {
            $extractedValue = $valueRetriever($value, $criteria);

            if (in_array($extractedValue, $duplicates, strict: true)) {
                return false;
            }

            $duplicates[] = $extractedValue;

            return true;
        };

        return (new Collection($elements))
            ->filter($callback)
            ->toArray();
    }

    public function supports(?UniqueCriteriaInterface $criteria): bool
    {
        return $criteria instanceof ByField;
    }
}
