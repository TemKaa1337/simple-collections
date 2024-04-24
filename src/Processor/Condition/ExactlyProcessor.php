<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Processor\Condition;

use Temkaa\SimpleCollections\Enum\ComparisonOperator;
use Temkaa\SimpleCollections\Model\Condition\Compare;
use Temkaa\SimpleCollections\Model\Condition\Exactly;
use Temkaa\SimpleCollections\Model\ConditionInterface;
use Temkaa\SimpleCollections\Trait\ValueRetrieverTrait;

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
