# These are simple array and object collections that provide convinient methods to manipulate collections;
To install this package type ```composer require temkaa-simple-collections``` in your project root directory.
## Quickstart
```
<?php declare(strict_types = 1);

use Temkaa\Collections\ArrayCollection;
use Temkaa\Collections\ObjectCollection;

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

        $collection = new ArrayCollection($array);
        var_dump(
            $collection->sortBy('id', 'desc')->all(),
            $collection->whereIn('product_id', [1, 2, 3])->all()
        );
    }

    public function someObjectFunction(): void
    {
        $result = Database::all() // Some database query
        
        $collection = new ObjectCollection($result);
        var_dump(
            $collection->sortBy('id', 'desc')->all(),
            $collection->whereIn('product_id', [1, 2, 3])->all()
        );
    }
}
```
## Functionality
### where(string $fieldName, mixed $fieldValue): Collection
Using this method you can retrieve array elements with specified value.  
```$collection->where('id', 2)```  
### where(string $fieldName, string $operator, mixed $value): Collection
Using this method you can retrieve array elements with operator rule.  
Allowed operators:  
- `==`
- `<>`
- `<`
- `>`
- `<=`
- `>=`
- `===`
- `!==`  
```$collection->where('id', '<', 5)```  
### where(array $options)
Using this method you can specify array of rules, like in first two examples given. This is equivalent to calling the previous method.
```
$collection->where([
    ['id', '>', 10],
    ['value', '===', 15]
])
```
### whereIn(string $fieldName, array $values)
Using this method you can get array of elements, which specified field value is in given array.  
```$collection->whereIn('id', [1, 2, 3])```  
### map(callable $function)
Using this function you can map through the array and cast the fiven function to all elements.  
```$collection->map(fn ($element) => $element['id']```  
### sort(string $field, string $sortMethod = 'asc')
Using this method you can sort the array.  
```$collection->sort('id', 'desc')```  
### isEmpty()
Using this method you will know if the collection is empty.  
```$collection->isEmpty()```  
### isNotEmpty
This function is opposite to function `isEmpty`.  
```$collection->isNotEmpty()```  
### all()
This method will return the result array.