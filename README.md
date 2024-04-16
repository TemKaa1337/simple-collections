Simple Collections
===
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.0-8892BF.svg?style=flat-square)](https://php.net/)
[![License](http://poser.pugx.org/temkaa/simple-collections/license)](https://packagist.org/packages/temkaa/simple-collections)
[![Total Downloads](http://poser.pugx.org/temkaa/simple-collections/downloads)](https://packagist.org/packages/temkaa/simple-collections)

# These are simple array and object collections that provide convenient methods to manipulate collections;
To install this package type ```composer require temkaa/simple-collections``` in your project root directory.
## Quickstart
```php
<?php declare(strict_types = 1);

use SimpleCollections\Collection;
use SimpleCollections\Enum\ComparisonOperator;
use SimpleCollections\Enum\SortOrder;
use SimpleCollections\Model\Condition\Compare;
use SimpleCollections\Model\Condition\Exactly;
use SimpleCollections\Model\Sort\ByField;

class SomeClass
{
    public function someArrayFunction(): void
    {
        $products = [
            ['id' => 2, 'name' => 'milk'],
            ['id' => 6, 'name' => 'bread'],
            ['id' => 1, 'name' => 'meat'],
            ['id' => 2, 'name' => 'juice'],
        ];

        var_dump(
            (new Collection($products))->sort(new ByField('name', SortOrder::Desc))->toArray(),
            (new Collection($products))
                ->where(new Compare(field: 'name', operator: ComparisonOperator::Greater, value: 2))
                ->toArray(),
            (new Collection($products))->where(new Exactly(field: 'id', value: 1))->toArray(),
        );
    }

    public function someObjectFunction(): void
    {
        $result = Database::all() // Some database query
        
        var_dump(
            (new Collection($products))->unique()->toArray(),
            (new Collection($products))
                ->map(static fn (object $element): int => $elment->getId())
                ->toArray(),
        );
    }
}
```  
## Functionality
### add(mixed $value, int|string|null $key = null): void
Adds a new element to collection.
```php
use SimpleCollections\Collection;

$collection = new Collection([]);
$collection->add(value: 'value');
// or
$collection = new Collection([]);
$collection->add(value: 'value', key: 'key');
```
### chunk(int $size): CollectionInterface[]
Chunks elements in collection by given size.
```php
use SimpleCollections\Collection;

$collection = new Collection(['element1', 'element2']);
$chunks = $collection->chunk(1);
```
### count(): int
Returns count of elements in Collection.
```php
use SimpleCollections\Collection;

$collection = new Collection(['element1', 'element2']);
$count = $collection->count();
```
### each(callable $callback): CollectionInterface
Executes provided callback on each collection element. If false is returned from callback, iteration stops.
```php
use SimpleCollections\Collection;

$collection = new Collection([$object1, $object2]);
$collection->each(static function (object $element): bool {
    if ($element->getId() === 1) {
        return false;
    }
    
    $element->setValue(10);
    
    return true;
});
```
### filter(callable $callback): CollectionInterface
Filters the collection with provided callback.
```php
use SimpleCollections\Collection;

$collection = new Collection([$object1, $object2]);
$newCollection = $collection->filter(static function (object $element): bool => $element->getId() > 10);
```
### first(): mixed
Returns first element from collection, `null` if collection is empty. 
```php
use SimpleCollections\Collection;

$collection = new Collection(['element1', 'element2']);
$first = $collection->first();
```
### has(mixed $value): bool
Returns true of element/key is found, false otherwise.
```php
use SimpleCollections\Collection;

$collection = new Collection(['element1', 'element2']);
$exists = $collection->has('element1'); // true
// or
$collection = new Collection(['key' => 'value']);
$exists = $collection->has('key'); // true
```
### isEmpty(): bool
Returns true if collection is empty, false otherwise.
```php
use SimpleCollections\Collection;

$collection = new Collection(['element1', 'element2']);
$isEmpty = $collection->isEmpty(); // false
```
### isNotEmpty(): bool
Opposite of `isEmpty` method. 
```php
use SimpleCollections\Collection;

$collection = new Collection(['element1', 'element2']);
$isNotEmpty = $collection->isNotEmpty(); // true
```
### last(): mixed
Returns last element of collection, null if collection is empty.
```php
use SimpleCollections\Collection;

$collection = new Collection(['element1', 'element2']);
$last = $collection->last();
```
### map(callable $callback): Collection
Creates new collection from provided callback.
```php
use SimpleCollections\Collection;

$collection = new Collection(['element1', 'element2']);
$mappedArray = $collection
    ->map(static fn (string $element): string => $element.'1')
    ->toArray(); // ['element11', 'element21']
```
### merge(CollectionInterface $collection, bool $recursive = false): CollectionInterface
Merges two collections with each other.
```php
use SimpleCollections\Collection;

$collection1 = new Collection(['element1', 'element2']);
$collection2 = new Collection(['element3', 'element4']);
$resultArray = $collection1
    ->merge($collection2)
    ->toArray(); // ['element1', 'element2', 'element3', 'element4']
// or
$collection1 = new Collection(['a' => 'element1', 'b' => 'element2']);
$collection2 = new Collection(['a' => 'element3', 'b' => 'element4']);
$resultArray = $collection1
    ->merge($collection2, recursive: true)
    ->toArray(); // ['a' => ['element1', 'element3'], 'b' => ['element2', 'element4']]
```
### remove(mixed $value): mixed
Removes provided element from collection, if element does not exist returns null.
```php
use SimpleCollections\Collection;

$collection = new Collection(['element1', 'element2']);
$removedElement = $collection->remove('element1'); // element1
// or
$collection = new Collection(['a' => 'element1', 'b' => 'element2']);
$removedElement = $collection->remove('a'); // element1
```
### slice(int $offset, ?int $length = null): CollectionInterface
Slices collection from given offset with provided length. If length is not defined - gets all elements from given offset.
```php
use SimpleCollections\Collection;

$collection = new Collection(['element1', 'element2', 'element3']);
$slice = $collection->slice(offset: 1)->toArray(); // ['element2', 'element3']
// or
$collection = new Collection(['element1', 'element2', 'element3']);
$slice = $collection->slice(offset: 1, limit: 0)->toArray(); // ['element2']
```
### toArray(): array
Returns collection elements.
```php
use SimpleCollections\Collection;

$collection = new Collection(['element1', 'element2', 'element3']);
$array = $collection->toArray(); // ['element1', 'element2', 'element3']
```
### sort(SortCriteriaInterface $criteria): CollectionInterface
Sorts collection by provided criteria.  
Sorting by provided callback:
```php
use SimpleCollections\Collection;
use SimpleCollections\Model\Sort\ByCallback;

$object1->setId(1);
$object2->setId(2);
$object3->setId(3);

$collection = new Collection([$object3, $object2, $object1]);
$sorted = $collection
    ->sort(new ByCallback(static fn (object $element): int => $element->getId()))
    ->toArray(); // [$object1, $object2, $object3]
```
Sorting by values:
```php
use SimpleCollections\Collection;
use SimpleCollections\Enum\SortOrder;
use SimpleCollections\Model\Sort\ByValues;

$collection = new Collection([3, 2, 1]);
$sorted = $collection->sort(new ByValues())->toArray(); // [1, 2, 3]
// or
$collection = new Collection([1, 2, 3]);
$sorted = $collection->sort(new ByValues(SortOrder::Desc))->toArray(); // 3, 2, 1]
```
Sorting by keys:
```php
use SimpleCollections\Collection;
use SimpleCollections\Enum\SortOrder;
use SimpleCollections\Model\Sort\ByKeys;

$collection = new Collection(['c' => 8, 'b' => 9, 'a' => 10]);
$sorted = $collection->sort(new ByKeys())->toArray(); // ['a' => 10, 'b' => 9, 'c' => 8]
// or
$collection = new Collection(['c' => 10, 'b' => 9, 'a' => 8]);
$sorted = $collection->sort(new ByKeys(SortOrder::Desc))->toArray(); // ['c' => 10, 'b' => 9, 'a' => 8]
```
Sorting by field:
```php
use SimpleCollections\Collection;
use SimpleCollections\Enum\SortOrder;
use SimpleCollections\Model\Sort\ByField;

$collection = new Collection([['field' => 10], ['field' => 5], []]);
$sorted = $collection->sort(new ByField(field: 'field'))->toArray(); // [[], ['field' => 5], ['field' => 10]

$collection = new Collection([['a' => 5], ['a' => 10], []]);
$sorted = $collection
    ->sort(new ByField(field: 'field', order: SortOrder::Desc))
    ->toArray(); [['a' => 10], ['a' => 5], []]
```
### sum(?SumCriteriaInterface $criteria = null): float|int
Returns sum by provided criteria.  
Sum by default:
```php
use SimpleCollections\Collection;

$collection = new Collection([1.1, 2, 3]);
$sum = $collection->sum(); // 6.1
```
Sum by field:
```php
use SimpleCollections\Collection;
use SimpleCollections\Model\Sum\ByField;

$collection = new Collection([['a' => 1], ['a' => 2]]);
$sum = $collection->sum(new ByField(field: 'a')); // 3
```
### unique(?UniqueCriteriaInterface $criteria = null): CollectionInterface
Returns unique elements by provided criteria.  
Unique by default:
```php
use SimpleCollections\Collection;

$collection = new Collection([1, 2]);
$unique = $collection->unique()->toArray(); // [1]
```
Unique by field:
```php
use SimpleCollections\Collection;
use SimpleCollections\Model\Unique\ByField;

$collection = new Collection([['a' => 1, 'b' => 2], ['a' => 1, 'b' => 3]]);
$unique = $collection->unique(new ByField(field: 'a'))->toArray(); // [['a' => 1, 'b' => 2]]
```
### where(ConditionInterface $condition): CollectionInterface
Returns filtered collection by provided condition.  
Where with exact hit:
```php
use SimpleCollections\Collection;
use SimpleCollections\Model\Condition\Exactly;

$collection = new Collection([1, 2]);
$compared = $collection->where(new Exactly(field: 'a', value: 1))->toArray(); // [1]
```
Where with comparison (full list of allowed comparison operators can be viewed in `SimpleCollections\Enum\ComparisonOperator`):
```php
use SimpleCollections\Collection;
use SimpleCollections\Enum\ComparisonOperator;
use SimpleCollections\Model\Condition\Compare;

$collection = new Collection([1, 2, 3, 4, 5]);
$compared = $collection
    ->where(new Compare(field: 'a', operator: ComparisonOperator::In, value: [1, 2]))
    ->toArray(); // [1, 2]
```
