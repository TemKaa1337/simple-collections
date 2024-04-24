<?php

declare(strict_types=1);

namespace Tests\Unit;

use stdClass;
use Temkaa\SimpleCollections\Collection;
use Temkaa\SimpleCollections\Collection\CollectionInterface;
use Temkaa\SimpleCollections\Model\ConditionInterface;
use Temkaa\SimpleCollections\Model\SortCriteriaInterface;
use Temkaa\SimpleCollections\Model\SumCriteriaInterface;
use Temkaa\SimpleCollections\Model\UniqueCriteriaInterface;

final class CollectionTest extends AbstractCollectionTest
{
    /**
     * @dataProvider getDataForAddTest
     */
    public function testAdd(array $sourceElements, array $resultElements, mixed $element, int|string|null $key): void
    {
        $collection = new Collection($sourceElements);
        $collection->add($element, $key);

        self::assertEquals($resultElements, $collection->toArray());
    }

    /**
     * @dataProvider getDataForChunkTest
     */
    public function testChunk(array $sourceElements, array $resultElements, int $size): void
    {
        $chunks = array_map(
            static fn (CollectionInterface $collection): array => $collection->toArray(),
            (new Collection($sourceElements))->chunk($size),
        );

        self::assertEquals($resultElements, $chunks);
    }

    /**
     * @dataProvider getDataForCountTest
     */
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

    /**
     * @dataProvider getDataForFilterTest
     */
    public function testFiler(array $sourceElements, array $resultElements, callable $callback): void
    {
        self::assertEquals($resultElements, (new Collection($sourceElements))->filter($callback)->toArray());
    }

    /**
     * @dataProvider getDataForFirstTest
     */
    public function testFirst(array $elements, ?int $expectedValue): void
    {
        self::assertEquals($expectedValue, (new Collection($elements))->first());
    }

    /**
     * @dataProvider getDataForHasTest
     */
    public function testHas(array $elements, mixed $element, bool $has): void
    {
        self::assertEquals($has, (new Collection($elements))->has($element));
    }

    /**
     * @dataProvider getDataForIsEmptyTest
     */
    public function testIsEmpty(array $elements, bool $expectedResult): void
    {
        self::assertEquals($expectedResult, (new Collection($elements))->isEmpty());
    }

    /**
     * @dataProvider getDataForIsNotEmptyTest
     */
    public function testIsNotEmpty(array $elements, bool $expectedResult): void
    {
        self::assertEquals($expectedResult, (new Collection($elements))->isNotEmpty());
    }

    /**
     * @dataProvider getDataForLastTest
     */
    public function testLast(array $elements, ?int $expectedValue): void
    {
        self::assertEquals($expectedValue, (new Collection($elements))->last());
    }

    /**
     * @dataProvider getDataForMapTest
     */
    public function testMap(array $sourceElements, array $resultElements, callable $callback): void
    {
        self::assertEquals($resultElements, (new Collection($sourceElements))->map($callback)->toArray());
    }

    /**
     * @dataProvider getDataForMergeTest
     */
    public function testMerge(
        array $sourceElements,
        array $mergeElements,
        array $resultElements,
        bool $isRecursive,
    ): void {
        self::assertEquals(
            $resultElements,
            (new Collection($sourceElements))->merge(new Collection($mergeElements), $isRecursive)->toArray(),
        );
    }

    /**
     * @dataProvider getDataForRemoveTest
     */
    public function testRemove(array $sourceElements, array $resultElements, mixed $element): void
    {
        $collection = new Collection($sourceElements);
        $collection->remove($element);

        self::assertEquals($resultElements, $collection->toArray());
    }

    /**
     * @dataProvider getDataForSliceTest
     */
    public function testSlice(array $sourceElements, array $resultElements, int $offset, ?int $length): void
    {
        self::assertEquals($resultElements, (new Collection($sourceElements))->slice($offset, $length)->toArray());
    }

    /**
     * @dataProvider getDataForSortByCallbackTest
     */
    public function testSortByCallback(
        array $sourceElements,
        array $resultElements,
        SortCriteriaInterface $criteria,
    ): void {
        self::assertEquals($resultElements, (new Collection($sourceElements))->sort($criteria)->toArray());
    }

    /**
     * @dataProvider getDataForSortByFieldTest
     */
    public function testSortByField(array $sourceElements, array $resultElements, SortCriteriaInterface $criteria): void
    {
        self::assertEquals($resultElements, (new Collection($sourceElements))->sort($criteria)->toArray());
    }

    /**
     * @dataProvider getDataForSortByKeysTest
     */
    public function testSortByKeys(array $sourceElements, array $resultElements, SortCriteriaInterface $criteria): void
    {
        self::assertEquals($resultElements, (new Collection($sourceElements))->sort($criteria)->toArray());
    }

    /**
     * @dataProvider getDataForSortByValuesTest
     */
    public function testSortByValues(
        array $sourceElements,
        array $resultElements,
        SortCriteriaInterface $criteria,
    ): void {
        self::assertEquals($resultElements, (new Collection($sourceElements))->sort($criteria)->toArray());
    }

    /**
     * @dataProvider getDataForSumByFieldTest
     */
    public function testSumByField(array $elements, SumCriteriaInterface $criteria, float|int $expectedSum): void
    {
        self::assertEquals($expectedSum, (new Collection($elements))->sum($criteria));
    }

    /**
     * @dataProvider getDataForSumDefaultTest
     */
    public function testSumDefault(array $elements, float|int $expectedSum): void
    {
        self::assertEquals($expectedSum, (new Collection($elements))->sum());
    }

    public function testToArray(): void
    {
        $elements = [1, 2, 3];

        self::assertEquals($elements, (new Collection($elements))->toArray());
    }

    /**
     * @dataProvider getDataForUniqueByFieldTest
     */
    public function testUniqueByField(
        array $sourceElements,
        array $resultElements,
        UniqueCriteriaInterface $criteria,
    ): void {
        self::assertEquals($resultElements, (new Collection($sourceElements))->unique($criteria)->toArray());
    }

    /**
     * @dataProvider getDataForUniqueDefaultTest
     */
    public function testUniqueDefault(array $sourceElements, array $resultElements): void
    {
        self::assertEquals($resultElements, (new Collection($sourceElements))->unique()->toArray());
    }

    /**
     * @dataProvider getDataForWhereCompareTest
     */
    public function testWhereCompare(array $sourceElements, array $resultElements, ConditionInterface $condition): void
    {
        self::assertEquals($resultElements, (new Collection($sourceElements))->where($condition)->toArray());
    }

    /**
     * @dataProvider getDataForWhereExactlyTest
     */
    public function testWhereExactly(array $sourceElements, array $resultElements, ConditionInterface $condition): void
    {
        self::assertEquals($resultElements, (new Collection($sourceElements))->where($condition)->toArray());
    }
}
