<?php

declare(strict_types=1);

namespace SimpleCollections\Processor\Sort;

use ReflectionException;
use SimpleCollections\Enum\SortOrder;
use SimpleCollections\Model\Sort\ByField;
use SimpleCollections\Model\SortCriteriaInterface;
use SimpleCollections\Trait\ValueRetrieverTrait;

final class ByFieldProcessor implements ProcessorInterface
{
    use ValueRetrieverTrait;

    /**
     * @param ByField $criteria
     *
     * @throws ReflectionException
     */
    public function process(array $elements, SortCriteriaInterface $criteria): array
    {
        $result = [];
        $valueRetriever = $this->retrieveCallable($criteria->field);

        foreach ($elements as $key => $value) {
            $result[$key] = $valueRetriever($value);
        }

        $criteria->order === SortOrder::Asc
            ? asort($result, $criteria->flags)
            : arsort($result, $criteria->flags);

        foreach (array_keys($result) as $key) {
            $result[$key] = $elements[$key];
        }

        return array_is_list($elements) ? array_values($result) : $result;
    }

    public function supports(SortCriteriaInterface $criteria): bool
    {
        return $criteria instanceof ByField;
    }
}
