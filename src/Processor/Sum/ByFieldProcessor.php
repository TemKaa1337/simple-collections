<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Processor\Sum;

use Temkaa\SimpleCollections\Collection;
use Temkaa\SimpleCollections\Model\Sum\ByField;
use Temkaa\SimpleCollections\Model\SumCriteriaInterface;
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
    public function process(array $elements, ?SumCriteriaInterface $criteria): float|int
    {
        $sum = 0;

        $valueRetriever = $this->retrieveCallable($criteria->field);

        Collection::make($elements)
            ->each(function (mixed $element) use (&$sum, $valueRetriever): true {
                $sum += $valueRetriever($element);

                return true;
            });

        return $sum;
    }

    public function supports(?SumCriteriaInterface $criteria): bool
    {
        return $criteria instanceof ByField;
    }
}
