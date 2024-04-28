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

            return match ($condition->operator) {
                ComparisonOperator::Equals         => $retrievedValue === $condition->value,
                ComparisonOperator::Greater        => $retrievedValue > $condition->value,
                ComparisonOperator::GreaterOrEqual => $retrievedValue >= $condition->value,
                ComparisonOperator::In             => in_array($retrievedValue, $condition->value, strict: true),
                ComparisonOperator::NotIn          => !in_array($retrievedValue, $condition->value, strict: true),
                ComparisonOperator::Less           => $retrievedValue < $condition->value,
                ComparisonOperator::LessOrEqual    => $retrievedValue <= $condition->value,
                ComparisonOperator::NotEquals      => $retrievedValue !== $condition->value,
                default                            => throw new LogicException(
                    "Condition \"{$condition->operator->name}\" is not implemented.",
                ),
            };
        };

        return (new Collection($elements))
            ->filter($callback)
            ->toArray();
    }

    public function supports(ConditionInterface $condition): bool
    {
        return $condition instanceof Compare;
    }
}
