<?php

declare(strict_types=1);

namespace SimpleCollections\Processor\Sum;

use SimpleCollections\Collection;
use SimpleCollections\Model\Sum\ByField;
use SimpleCollections\Model\SumCriteriaInterface;
use SimpleCollections\Trait\ValueRetrieverTrait;

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

        (new Collection($elements))
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
