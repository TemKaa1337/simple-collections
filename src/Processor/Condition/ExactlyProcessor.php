<?php

declare(strict_types=1);

namespace SimpleCollections\Processor\Condition;

use SimpleCollections\Enum\ComparisonOperator;
use SimpleCollections\Model\Condition\Compare;
use SimpleCollections\Model\Condition\Exactly;
use SimpleCollections\Model\ConditionInterface;
use SimpleCollections\Trait\ValueRetrieverTrait;

final class ExactlyProcessor implements ProcessorInterface
{
    use ValueRetrieverTrait;

    /**
     * @param Exactly $condition
     */
    public function process(array $elements, ConditionInterface $condition): array
    {
        return (new CompareProcessor())
            ->process($elements, new Compare($condition->field, ComparisonOperator::Equals, $condition->value));
    }

    public function supports(ConditionInterface $condition): bool
    {
        return $condition instanceof Exactly;
    }
}
