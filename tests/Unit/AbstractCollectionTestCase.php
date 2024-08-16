<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use stdClass;
use Temkaa\SimpleCollections\Enum\ComparisonOperator;
use Temkaa\SimpleCollections\Enum\SortOrder;
use Temkaa\SimpleCollections\Model\Condition\Compare;
use Temkaa\SimpleCollections\Model\Condition\Exactly;
use Temkaa\SimpleCollections\Model\Sort\ByCallback;
use Temkaa\SimpleCollections\Model\Sort\ByField;
use Temkaa\SimpleCollections\Model\Sort\ByKeys;
use Temkaa\SimpleCollections\Model\Sort\ByValues;
use Temkaa\SimpleCollections\Model\Sum\ByField as SumByField;
use Temkaa\SimpleCollections\Model\Unique\ByField as UniqueByField;
use Tests\Stub\ClassWithProperty;

abstract class AbstractCollectionTestCase extends TestCase
{
    public static function getDataForAddTest(): iterable
    {
        yield [[1, 2, 3], [1, 2, 3, 3], 3, null];

        $el1 = new stdClass();
        $el1->test = 1;
        $el2 = new stdClass();
        $el2->test = 2;
        $el3 = new stdClass();
        $el3->test = 2;

        yield [[$el1, $el2], [$el1, $el2, $el3], $el3, null];

        yield [['a' => 1], ['a' => 1, 'b' => 1], 1, 'b'];
    }

    public static function getDataForChunkTest(): iterable
    {
        yield [[1, 2, 3], [[1], [2], [3]], 1];
        yield [[1, 2, 3], [[1, 2], [3]], 2];
        yield [[1, 2, 3], [[1, 2, 3]], 3];
        yield [['a' => 1, 'b' => 1, 'c' => 1], [['a' => 1, 'b' => 1], ['c' => 1]], 2];
    }

    public static function getDataForCountTest(): iterable
    {
        yield [[1, 2, 3], 3];
        yield [[], 0];
    }

    public static function getDataForFilterTest(): iterable
    {
        yield [[1, 2, 3], [1], static fn (int $element): bool => $element === 1];
        yield [[[1, 2], [3, 4]], [[1, 2]], static fn (array $element): bool => $element === [1, 2]];

        $el1 = new stdClass();
        $el1->test = 1;
        $el2 = new stdClass();
        $el2->test = 2;

        yield [[$el1, $el2], [$el1], static fn (object $element): bool => $element->test === 1];
    }

    public static function getDataForFirstTest(): iterable
    {
        yield [[1, 2, 3], 1];
        yield [[], null];
    }

    public static function getDataForHasTest(): iterable
    {
        yield [[1, 2, 3], 1, true];
        yield [[1, 2, 3], 4, false];

        $el1 = new stdClass();
        $el1->test = 1;
        $el2 = new stdClass();
        $el2->test = 2;
        $el3 = new stdClass();
        $el3->test = 2;

        yield [[$el1, $el2], $el3, false];
        yield [[$el1, $el2, $el3], $el3, true];

        yield [['a' => 1], 'a', true];
        yield [['a' => 1], 'b', false];
    }

    public static function getDataForIsEmptyTest(): iterable
    {
        yield [[1, 2, 3], false];
        yield [[], true];
    }

    public static function getDataForIsNotEmptyTest(): iterable
    {
        yield [[1, 2, 3], true];
        yield [[], false];
    }

    public static function getDataForLastTest(): iterable
    {
        yield [[1, 2, 3], 3];
        yield [[], null];
    }

    public static function getDataForMapTest(): iterable
    {
        yield [[1, 2, 3], [2, 4, 6], static fn (int $element): int => $element * 2];
        yield [[[1, 2], [3, 4]], [[1, 2, 0], [3, 4, 0]], static fn (array $element): array => [...$element, 0]];

        $el1 = new stdClass();
        $el1->test = 1;
        $el2 = new stdClass();
        $el2->test = 2;

        yield [[$el1, $el2], [[$el1], [$el2]], static fn (object $element): array => [$element]];
    }

    public static function getDataForMergeTest(): iterable
    {
        yield [[1, 2, 3], [3], [1, 2, 3, 3], false];
        yield [['a' => 1], ['b' => 1], ['a' => 1, 'b' => 1], false];
        yield [['a' => 1], ['a' => 1], ['a' => 1], false];
        yield [['a' => 1], ['a' => 2], ['a' => [1, 2]], true];
    }

    public static function getDataForRemoveTest(): iterable
    {
        yield [[1, 2, 3], [1, 2], 3];
        yield [[1, 2, 3], [1, 3], 2];
        yield [[1, 2, 3], [1, 2, 3], 4];

        $el1 = new stdClass();
        $el1->test = 1;
        $el2 = new stdClass();
        $el2->test = 2;
        $el3 = new stdClass();
        $el3->test = 2;

        yield [[$el1, $el2], [$el1], $el2];
        yield [[$el1, $el2], [$el1, $el2], $el3];

        yield [['a' => 1, 'b' => 2], ['b' => 2], 'a'];
        yield [['a' => 1, 'b' => 2], ['a' => 1, 'b' => 2], 1];
    }

    public static function getDataForSliceTest(): iterable
    {
        yield [[1, 2, 3], [3], 2, 1];
        yield [[1, 2, 3], [2, 3], 1, null];

        yield [['a' => 10, 'b' => 9, 'c' => 8], ['a' => 10, 'b' => 9], 0, 2];
        yield [['a' => 10, 'b' => 9, 'c' => 8], ['b' => 9, 'c' => 8], 1, null];
    }

    public static function getDataForSortByCallbackTest(): iterable
    {
        $callback = static function (int $a, int $b): int {
            if ($a === $b) {
                return 0;
            }

            return $a > $b ? -1 : 1;
        };
        yield [['c' => 10, 'b' => 8, 'a' => 9], ['c' => 10, 'a' => 9, 'b' => 8], new ByCallback($callback)];
        $callback = static function (string $a, string $b): int {
            if ($a === $b) {
                return 0;
            }

            return $a > $b ? -1 : 1;
        };
        yield [
            ['c' => 10, 'b' => 8, 'a' => 9],
            ['a' => 9, 'b' => 8, 'c' => 10],
            new ByCallback($callback, sortValues: false),
        ];

        $callback = static function (int $a, int $b): int {
            if ($a === $b) {
                return 0;
            }

            return $a > $b ? 1 : -1;
        };
        yield [['c' => 10, 'b' => 9, 'a' => 8], ['a' => 8, 'b' => 9, 'c' => 10], new ByCallback($callback)];

        $callback = static function (string $a, string $b): int {
            if (strlen($a) === strlen($b)) {
                return 0;
            }

            return strlen($a) > strlen($b) ? 1 : -1;
        };
        yield [
            ['aaa' => 8, 'aa' => 9, 'a' => 10],
            ['a' => 10, 'aa' => 9, 'aaa' => 8],
            new ByCallback($callback, sortValues: false),
        ];

        $callback = static function (string $a, string $b): int {
            if (strlen($a) === strlen($b)) {
                return 0;
            }

            return strlen($a) > strlen($b) ? -1 : 1;
        };
        yield [
            ['a' => 10, 'aa' => 9, 'aaa' => 8],
            ['aaa' => 8, 'aa' => 9, 'a' => 10],
            new ByCallback($callback, sortValues: false),
        ];

        $callback = static function (array $a, array $b): int {
            if ($a['a'] === $b['a']) {
                return 0;
            }

            return $a['a'] > $b['a'] ? 1 : -1;
        };
        yield [[['a' => 10], ['a' => 9], ['a' => 8]], [['a' => 8], ['a' => 9], ['a' => 10]], new ByCallback($callback)];

        $callback = static function (array $a, array $b): int {
            if ($a['a'] === $b['a']) {
                return 0;
            }

            return $a['a'] > $b['a'] ? -1 : 1;
        };
        yield [[['a' => 8], ['a' => 9], ['a' => 10]], [['a' => 10], ['a' => 9], ['a' => 8]], new ByCallback($callback)];
    }

    public static function getDataForSortByFieldTest(): iterable
    {
        yield [[['a' => 10], ['a' => 5], []], [[], ['a' => 5], ['a' => 10]], new ByField(field: 'a')];
        yield [
            [['a' => 5], ['a' => 10], []],
            [['a' => 10], ['a' => 5], []],
            new ByField(field: 'a', order: SortOrder::Desc),
        ];

        $el1 = new stdClass();
        $el1->test = 1;
        $el2 = new stdClass();
        $el2->test = 2;
        $el3 = new stdClass();
        $el3->test = 3;

        yield [[$el3, $el2, $el1], [$el1, $el2, $el3], new ByField(field: 'test')];
        yield [[$el1, $el2, $el3], [$el3, $el2, $el1], new ByField(field: 'test', order: SortOrder::Desc)];

        yield [
            ['a' => ['a' => 10], 'b' => ['a' => 5], 'c' => []],
            ['b' => ['a' => 5], 'a' => ['a' => 10], 'c' => []],
            new ByField(field: 'a'),
        ];
        yield [
            ['a' => ['a' => 5], 'b' => ['a' => 10], 'c' => []],
            ['c' => [], 'b' => ['a' => 10], 'a' => ['a' => 5]],
            new ByField(field: 'a', order: SortOrder::Desc),
        ];

        $el1 = new ClassWithProperty(1);
        $el2 = new ClassWithProperty(2);
        $el3 = new ClassWithProperty(3);

        yield [[$el3, $el2, $el1], [$el1, $el2, $el3], new ByField(field: 'test')];
        yield [[$el1, $el2, $el3], [$el3, $el2, $el1], new ByField(field: 'test', order: SortOrder::Desc)];
    }

    public static function getDataForSortByKeysTest(): iterable
    {
        yield [['c' => 8, 'b' => 9, 'a' => 10], ['a' => 10, 'b' => 9, 'c' => 8], new ByKeys(SortOrder::Asc)];
        yield [['a' => 10, 'b' => 9, 'c' => 8], ['c' => 8, 'b' => 9, 'a' => 10], new ByKeys(SortOrder::Desc)];
    }

    public static function getDataForSortByValuesTest(): iterable
    {
        yield [['c', 'b', 'a'], ['a', 'b', 'c'], new ByValues(SortOrder::Asc)];
        yield [['a', 'b', 'c'], ['c', 'b', 'a'], new ByValues(SortOrder::Desc)];

        yield [[3, 2, 1], [1, 2, 3], new ByValues(SortOrder::Asc)];
        yield [[1, 2, 3], [3, 2, 1], new ByValues(SortOrder::Desc)];

        $el1 = new stdClass();
        $el1->test = 1;
        $el2 = new stdClass();
        $el2->test = 2;

        yield [[$el2, $el1], [$el1, $el2], new ByValues(SortOrder::Asc)];
        yield [[$el1, $el2], [$el2, $el1], new ByValues(SortOrder::Desc)];

        yield [['a' => 10, 'b' => 9, 'c' => 8], ['c' => 8, 'b' => 9, 'a' => 10], new ByValues(SortOrder::Asc)];
        yield [['a' => 8, 'b' => 9, 'c' => 10], ['c' => 10, 'b' => 9, 'a' => 8], new ByValues(SortOrder::Desc)];
    }

    public static function getDataForSumByFieldTest(): iterable
    {
        yield [[['a' => 1], ['a' => 2], ['a' => 3]], new SumByField(field: 'a'), 6];

        $el1 = new stdClass();
        $el1->test = 1;
        $el2 = new stdClass();
        $el2->test = 2;
        $el3 = new stdClass();
        $el3->test = 3;
        yield [[$el1, $el2, $el3], new SumByField(field: 'test'), 6];
    }

    public static function getDataForSumDefaultTest(): iterable
    {
        yield [[1, 2, 3, 3], 9];
        yield [[1, 2.2, 3.3, 3], 9.5];
    }

    public static function getDataForUniqueByFieldTest(): iterable
    {
        yield [[['a' => 1], ['a' => 1], ['a' => 2]], [['a' => 1], ['a' => 2]], new UniqueByField(field: 'a')];
        yield [
            [['a' => 1, 'b' => 2], ['a' => 1, 'b' => 3], ['a' => 2, 'b' => 4]],
            [['a' => 1, 'b' => 2], ['a' => 2, 'b' => 4]],
            new UniqueByField(field: 'a'),
        ];

        $el1 = new stdClass();
        $el1->test = 1;
        $el2 = new stdClass();
        $el2->test = 2;
        yield [
            [['a' => $el1], ['a' => $el2], ['a' => $el1]],
            [['a' => $el1], ['a' => $el2]],
            new UniqueByField(field: 'a'),
        ];
    }

    public static function getDataForUniqueDefaultTest(): iterable
    {
        yield [[1, 2, 3, 3], [1, 2, 3]];
        yield [['a', 'b', 'b', 'c'], ['a', 'b', 'c']];

        $el1 = new stdClass();
        $el1->test = 1;
        $el2 = new stdClass();
        $el2->test = 2;
        yield [[$el1, $el2, $el1, $el2], [$el1, $el2]];
    }

    public static function getDataForWhereCompareTest(): iterable
    {
        yield [
            [['a' => 1], ['a' => 2], ['a' => 3]],
            [['a' => 3]],
            new Compare(field: 'a', operator: ComparisonOperator::Greater, value: 2),
        ];
        yield [
            [['a' => 1], ['a' => 2], ['a' => 3]],
            [['a' => 2], ['a' => 3]],
            new Compare(field: 'a', operator: ComparisonOperator::GreaterOrEqual, value: 2),
        ];
        yield [
            [['a' => 1], ['a' => 2], ['a' => 3]],
            [['a' => 2], ['a' => 3]],
            new Compare(field: 'a', operator: ComparisonOperator::In, value: [2, 3]),
        ];
        yield [
            [['a' => 1], ['a' => 2], ['a' => 3]],
            [['a' => 1]],
            new Compare(field: 'a', operator: ComparisonOperator::NotIn, value: [2, 3]),
        ];
        yield [
            [['a' => 1], ['a' => 2], ['a' => 3]],
            [['a' => 1], ['a' => 3]],
            new Compare(field: 'a', operator: ComparisonOperator::NotEquals, value: 2),
        ];
        yield [
            [['a' => 1], ['a' => 2], ['a' => 3]],
            [['a' => 1]],
            new Compare(field: 'a', operator: ComparisonOperator::Less, value: 2),
        ];
        yield [
            [['a' => 1], ['a' => 2], ['a' => 3]],
            [['a' => 1], ['a' => 2]],
            new Compare(field: 'a', operator: ComparisonOperator::LessOrEqual, value: 2),
        ];

        $el1 = new stdClass();
        $el1->test = 1;
        $el2 = new stdClass();
        $el2->test = 2;
        $el3 = new stdClass();
        $el3->test = 3;
        yield [
            [$el1, $el2, $el3],
            [$el3],
            new Compare(field: 'test', operator: ComparisonOperator::Greater, value: 2),
        ];
        yield [
            [$el1, $el2, $el3],
            [$el2, $el3],
            new Compare(field: 'test', operator: ComparisonOperator::GreaterOrEqual, value: 2),
        ];
        yield [
            [$el1, $el2, $el3],
            [$el2, $el3],
            new Compare(field: 'test', operator: ComparisonOperator::In, value: [2, 3]),
        ];
        yield [
            [$el1, $el2, $el3],
            [$el1],
            new Compare(field: 'test', operator: ComparisonOperator::NotIn, value: [2, 3]),
        ];
        yield [
            [$el1, $el2, $el3],
            [$el1, $el3],
            new Compare(field: 'test', operator: ComparisonOperator::NotEquals, value: 2),
        ];
        yield [
            [$el1, $el2, $el3],
            [$el1],
            new Compare(field: 'test', operator: ComparisonOperator::Less, value: 2),
        ];
        yield [
            [$el1, $el2, $el3],
            [$el1, $el2],
            new Compare(field: 'test', operator: ComparisonOperator::LessOrEqual, value: 2),
        ];
    }

    public static function getDataForWhereExactlyTest(): iterable
    {
        yield [[['a' => 1], ['a' => 2], ['a' => 3]], [['a' => 1]], new Exactly(field: 'a', value: 1)];

        $el1 = new stdClass();
        $el1->test = 1;
        $el2 = new stdClass();
        $el2->test = 2;
        $el3 = new stdClass();
        $el3->test = 3;
        yield [[$el1, $el2, $el3], [$el1], new Exactly(field: 'test', value: 1)];
    }
}
