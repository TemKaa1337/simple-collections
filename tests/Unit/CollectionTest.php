<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use stdClass;
use Temkaa\SimpleCollections\Collection;
use Temkaa\SimpleCollections\Collection\CollectionInterface;
use Temkaa\SimpleCollections\Model\ConditionInterface;
use Temkaa\SimpleCollections\Model\SortCriteriaInterface;
use Temkaa\SimpleCollections\Model\SumCriteriaInterface;
use Temkaa\SimpleCollections\Model\UniqueCriteriaInterface;

final class CollectionTest extends AbstractCollectionTestCase
{
    #[DataProvider('getDataForAddTest')]
    public function testAdd(array $sourceElements, array $resultElements, mixed $element, int|string|null $key): void
    {
        $collection = new Collection($sourceElements);
        $collection->add($element, $key);

        self::assertEquals($resultElements, $collection->toArray());
    }

    #[DataProvider('getDataForChunkTest')]
    public function testChunk(array $sourceElements, array $resultElements, int $size): void
    {
        $chunks = array_map(
            static fn (CollectionInterface $collection): array => $collection->toArray(),
            (new Collection($sourceElements))->chunk($size),
        );

        self::assertEquals($resultElements, $chunks);
    }

    #[DataProvider('getDataForCountTest')]
    public function testCount(array $elements, int $expectedCount): void
    {
        self::assertEquals($expectedCount, (new Collection($elements))->count());
    }

    public function testEach(): void
    {
        $eachCallback = static function (object $element, int $key): bool {
            if ($key === 2) {
                return false;
            }

            $element->value *= 2;

            return true;
        };

        $mapCallback = static fn (object $element): int => $element->value;

        $el1 = new stdClass();
        $el1->value = 1;
        $el2 = new stdClass();
        $el2->value = 2;
        $el3 = new stdClass();
        $el3->value = 3;
        $el4 = new stdClass();
        $el4->value = 4;

        $result = (new Collection([$el1, $el2, $el3, $el4]))
            ->each($eachCallback)
            ->map($mapCallback)
            ->toArray();

        self::assertEquals(
            [2, 4, 3, 4],
            $result,
        );
    }

    #[DataProvider('getDataForFilterTest')]
    public function testFiler(array $sourceElements, array $resultElements, callable $callback): void
    {
        self::assertEquals($resultElements, (new Collection($sourceElements))->filter($callback)->toArray());
    }

    #[DataProvider('getDataForFirstTest')]
    public function testFirst(array $elements, ?int $expectedValue): void
    {
        self::assertEquals($expectedValue, (new Collection($elements))->first());
    }

    #[DataProvider('getDataForHasTest')]
    public function testHas(array $elements, mixed $element, bool $has): void
    {
        self::assertEquals($has, (new Collection($elements))->has($element));
    }

    #[DataProvider('getDataForIsEmptyTest')]
    public function testIsEmpty(array $elements, bool $expectedResult): void
    {
        self::assertEquals($expectedResult, (new Collection($elements))->isEmpty());
    }

    #[DataProvider('getDataForIsNotEmptyTest')]
    public function testIsNotEmpty(array $elements, bool $expectedResult): void
    {
        self::assertEquals($expectedResult, (new Collection($elements))->isNotEmpty());
    }

    #[DataProvider('getDataForLastTest')]
    public function testLast(array $elements, ?int $expectedValue): void
    {
        self::assertEquals($expectedValue, (new Collection($elements))->last());
    }

    #[DataProvider('getDataForMapTest')]
    public function testMap(array $sourceElements, array $resultElements, callable $callback): void
    {
        self::assertEquals($resultElements, (new Collection($sourceElements))->map($callback)->toArray());
    }

    #[DataProvider('getDataForMergeTest')]
    public function testMerge(
        array $sourceElements,
        array $mergeElements,
        array $resultElements,
        bool $isRecursive,
    ): void {
        // needed to pass infection mutation testing for default isRecursive parameter change
        $collection = new Collection($sourceElements);
        $resultCollection = $isRecursive
            ? $collection->merge(new Collection($mergeElements), true)
            : $collection->merge(new Collection($mergeElements));

        self::assertEquals(
            $resultElements,
            $resultCollection->toArray(),
        );
    }

    #[DataProvider('getDataForRemoveTest')]
    public function testRemove(array $sourceElements, array $resultElements, mixed $element): void
    {
        $collection = new Collection($sourceElements);
        $collection->remove($element);

        self::assertEquals($resultElements, $collection->toArray());
    }

    #[DataProvider('getDataForSliceTest')]
    public function testSlice(array $sourceElements, array $resultElements, int $offset, ?int $length): void
    {
        self::assertEquals($resultElements, (new Collection($sourceElements))->slice($offset, $length)->toArray());
    }

    #[DataProvider('getDataForSortByCallbackTest')]
    public function testSortByCallback(
        array $sourceElements,
        array $resultElements,
        SortCriteriaInterface $criteria,
    ): void {
        self::assertEquals($resultElements, (new Collection($sourceElements))->sort($criteria)->toArray());
    }

    #[DataProvider('getDataForSortByFieldTest')]
    public function testSortByField(array $sourceElements, array $resultElements, SortCriteriaInterface $criteria): void
    {
        self::assertEquals($resultElements, (new Collection($sourceElements))->sort($criteria)->toArray());
    }

    #[DataProvider('getDataForSortByKeysTest')]
    public function testSortByKeys(array $sourceElements, array $resultElements, SortCriteriaInterface $criteria): void
    {
        self::assertEquals($resultElements, (new Collection($sourceElements))->sort($criteria)->toArray());
    }

    #[DataProvider('getDataForSortByValuesTest')]
    public function testSortByValues(
        array $sourceElements,
        array $resultElements,
        SortCriteriaInterface $criteria,
    ): void {
        self::assertEquals($resultElements, (new Collection($sourceElements))->sort($criteria)->toArray());
    }

    #[DataProvider('getDataForSumByFieldTest')]
    public function testSumByField(array $elements, SumCriteriaInterface $criteria, float|int $expectedSum): void
    {
        self::assertEquals($expectedSum, (new Collection($elements))->sum($criteria));
    }

    #[DataProvider('getDataForSumDefaultTest')]
    public function testSumDefault(array $elements, float|int $expectedSum): void
    {
        self::assertEquals($expectedSum, (new Collection($elements))->sum());
    }

    public function testToArray(): void
    {
        $elements = [1, 2, 3];

        self::assertEquals($elements, (new Collection($elements))->toArray());
    }

    #[DataProvider('getDataForUniqueByFieldTest')]
    public function testUniqueByField(
        array $sourceElements,
        array $resultElements,
        UniqueCriteriaInterface $criteria,
    ): void {
        self::assertEquals($resultElements, (new Collection($sourceElements))->unique($criteria)->toArray());
    }

    #[DataProvider('getDataForUniqueDefaultTest')]
    public function testUniqueDefault(array $sourceElements, array $resultElements): void
    {
        self::assertEquals($resultElements, (new Collection($sourceElements))->unique()->toArray());
    }

    #[DataProvider('getDataForWhereCompareTest')]
    public function testWhereCompare(array $sourceElements, array $resultElements, ConditionInterface $condition): void
    {
        self::assertEquals($resultElements, (new Collection($sourceElements))->where($condition)->toArray());
    }

    #[DataProvider('getDataForWhereExactlyTest')]
    public function testWhereExactly(array $sourceElements, array $resultElements, ConditionInterface $condition): void
    {
        self::assertEquals($resultElements, (new Collection($sourceElements))->where($condition)->toArray());
    }
}
