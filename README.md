Simple Collections
===
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.0-8892BF.svg?style=flat-square)](https://php.net/)
[![License](http://poser.pugx.org/temkaa/simple-collections/license)](https://packagist.org/packages/temkaa/simple-collections)
[![Total Downloads](http://poser.pugx.org/temkaa/simple-collections/downloads)](https://packagist.org/packages/temkaa/simple-collections)
===
# These are simple array and object collections that provide convinient methods to manipulate collections;
To install this package type ```composer require temkaa/simple-collections``` in your project root directory.
## Quickstart
```
<?php declare(strict_types = 1);

use SimpleCollections\Collections\Collection;

class SomeClass
{
    public function someArrayFunction(): void
    {
        $array = [
            ['product_id' => 2, 'product_name' => 'milk'],
            ['product_id' => 6, 'product_name' => 'bread'],
            ['product_id' => 1, 'product_name' => 'meat'],
            ['product_id' => 2, 'product_name' => 'juice'],
        ];

        $collection = Collection::init($array);
        var_dump(
            $collection->sortBy('id', 'desc')->all(),
            $collection->whereIn('product_id', [1, 2, 3])->all()
        );
    }

    public function someObjectFunction(): void
    {
        $result = Database::all() // Some database query
        
        $collection = Collection::init($result);
        var_dump(
            $collection->sortBy('id', 'desc')->all(),
            $collection->whereIn('product_id', [1, 2, 3])->all()
        );
    }
}
```
## Functionality
### static init(array $array): ArrayCollection|ObjectCollection
This method returns an instance of collection. If the provided array has incorrect format, then `InvalidInputFormatException` will be thrown.
```Collection::init([])```
### where(string $fieldName, mixed $fieldValue): Collection
Using this method you can retrieve array elements with specified value.  
```$collection->where('id', 2)```  
### where(string $fieldName, string $operator, mixed $value): Collection
Using this method you can retrieve array elements with operator rule.  
If you will pass incorrent operator, the `InvalidOperatorException` will ge thrown.  
Example: ```$collection->where('id', '<', 5)```   
Allowed operators:  
- `==`
- `<>`
- `<`
- `>`
- `<=`
- `>=`
- `===`
- `!==`    
### where(array $options): Collection
Using this method you can specify array of rules, like in first two examples given. This is equivalent to calling the previous method.
```
$collection->where([
    ['id', '>', 10],
    ['value', '===', 15]
])
```
### whereIn(string $fieldName, array $values): Collection
Using this method you can get array of elements, which specified field value is in given array.  
```$collection->whereIn('id', [1, 2, 3])```  
### map(callable $function): array
Using this function you can map through the array and cast the fiven function to all elements.  
```$collection->map(fn ($element) => $element['id']```  
### reject(callable $function): Collection
Using this method you can delete items by any rule. In given example collection will delete all elements, which `id` parameter equals to `2`  
```$collection->reject(fn ($element) => $element['id'] === 2)```  
### sort(string $field, string $sortMethod = 'asc'): Collection
Using this method you can sort the array.  
```$collection->sort('id', 'desc')```  
### filter(callable $fn): Collection  
Using this method you can filter collection using callback.  
```$collection->filter(fn ($element) => $element['a'] + $element['b'] > 10)```  
### isEmpty(): bool
Using this method you will know if the collection is empty.  
```$collection->isEmpty()```  
### isNotEmpty(): bool
This function is opposite to function `isEmpty`.  
```$collection->isNotEmpty()```  
### count(): array
This method will return count of items in collection.  
```$collection->count()```  
### first(): ?array|object
This method will return first collection item (return type depends on if collection is object or array). If the collection is empty, `null` will be returned.  
```$collection->first()```  
### last(): ?array|object
This method will return last collection item (return type depends on if collection is object or array). If the collection is empty, `null` will be returned.  
```$collection->last()```  
### all(): array
This method will return the result array.  
```$collection->all()```