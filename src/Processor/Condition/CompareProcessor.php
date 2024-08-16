<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Processor\Condition;

use LogicException;
use Temkaa\SimpleCollections\Collection;
use Temkaa\SimpleCollections\Enum\ComparisonOperator;
use Temkaa\SimpleCollections\Model\Condition\Compare;
use Temkaa\SimpleCollections\Model\ConditionInterface;
use Temkaa\SimpleCollections\Trait\ValueRetrieverTrait;

/**
 * @internal
 */
final class CompareProcessor implements ProcessorInterface
{
    use ValueRetrieverTrait;

    /**
     * @param Compare $condition
     */
    public function process(array $elements, ConditionInterface $condition): array
    {
        $valueRetriever = $this->retrieveCallable($condition->field);

        $callback = function (mixed $element) use ($condition, $valueRetriever): bool {
            $retrievedValue = $valueRetriever($element);

            return $this->compare($retrievedValue, $condition);
        };

        return Collection::make($elements)
            ->filter($callback)
            ->toArray();
    }

    public function supports(ConditionInterface $condition): bool
    {
        return $condition instanceof Compare;
    }

    private function compare(mixed $value, Compare $condition): bool
    {
        return match ($condition->operator) {
            ComparisonOperator::Equals             => $value === $condition->value,
            ComparisonOperator::GreaterThan        => $value > $condition->value,
            ComparisonOperator::GreaterThanOrEqual => $value >= $condition->value,
            ComparisonOperator::In                 => in_array($value, $condition->value, strict: true),
            ComparisonOperator::NotIn              => !in_array($value, $condition->value, strict: true),
            ComparisonOperator::LessThan           => $value < $condition->value,
            ComparisonOperator::LessThanOrEqual    => $value <= $condition->value,
            ComparisonOperator::NotEquals          => $value !== $condition->value,
            default                                => throw new LogicException(
                "Condition \"{$condition->operator->name}\" is not implemented.",
            ),
        };
    }
}
