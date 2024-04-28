<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Processor\Unique;

use Temkaa\SimpleCollections\Collection;
use Temkaa\SimpleCollections\Model\Unique\ByField;
use Temkaa\SimpleCollections\Model\UniqueCriteriaInterface;
use Temkaa\SimpleCollections\Trait\ValueRetrieverTrait;

/**
 * @internal
 */
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
