<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SimpleCollections\Collections\Collection;
use SimpleCollections\Exceptions\InvalidOperatorException;

final class ArrayCollectionTest extends TestCase
{
    protected array $input = [
        ['a' => 20, 'b' => 20],
        ['a' => 25, 'b' => 25],
        ['a' => 30, 'b' => 5],
        ['a' => 20, 'b' => 2],
        ['a' => 20, 'b' => 59],
        ['a' => 77, 'b' => 66]
    ];

    public function testIsCollectionEmptyWhenCollectionIsEmpty(): void
    {
        $this->assertEquals(
            true,
            Collection::init([])->isEmpty()
        );
    }

    public function testIsCollectionEmptyWhenCollectionIsNotEmpty(): void
    {
        $this->assertEquals(
            false,
            Collection::init($this->input)->isEmpty()
        );
    }

    public function testIsArrayMatchesAfterWhereMethodWith2Arguments(): void
    {
        $this->assertEquals(
            [
                ['a' => 20, 'b' => 20],
                ['a' => 20, 'b' => 2],
                ['a' => 20, 'b' => 59]
            ],
            Collection::init($this->input)->where('a', 20)->all()
        );
    }

    public function testIsArrayMatchesDefaultIfGivenKeyDoesntExist(): void
    {
        $this->assertEquals(
            [],
            Collection::init($this->input)->where('c', 20)->all()
        );
    }

    public function testIvalidOperandEquals(): void
    {
        $this->expectException(InvalidOperatorException::class);
        Collection::init($this->input)->where('a', '=', 10)->all();
    }

    public function testInvalidOperatorNotEquals(): void
    {
        $this->expectException(InvalidOperatorException::class);
        Collection::init($this->input)->where('a', '!=', 20)->where('a', '!=', 30)->all();
    }

    public function testIsArrayMatchesAfterWhereMethodWith3Arguments(): void
    {
        $this->assertEquals(
            [
                ['a' => 25, 'b' => 25],
                ['a' => 30, 'b' => 5],
                ['a' => 77, 'b' => 66]
            ],
            Collection::init($this->input)->where('a', '>', 20)->all()
        );

        $this->assertEquals(
            [
                ['a' => 25, 'b' => 25],
                ['a' => 30, 'b' => 5],
                ['a' => 77, 'b' => 66]
            ],
            Collection::init($this->input)->where('a', '>', 10)->where('a', '>', 20)->all()
        );

        $this->assertEquals(
            [                
                ['a' => 25, 'b' => 25],
                ['a' => 30, 'b' => 5],
                ['a' => 77, 'b' => 66]
            ],
            Collection::init($this->input)->where('a', '!==', 20)->all()
        );

        $this->assertEquals(
            [                
                ['a' => 25, 'b' => 25],
                ['a' => 77, 'b' => 66]
            ],
            Collection::init($this->input)->where('a', '!==', 20)->where('a', '!==', 30)->all()
        );

        $this->assertEquals(
            [],
            Collection::init($this->input)->where('a', '<', 20)->all()
        );

        $this->assertEquals(
            [                
                ['a' => 20, 'b' => 20],
                ['a' => 25, 'b' => 25],
                ['a' => 20, 'b' => 2],
                ['a' => 20, 'b' => 59],
            ],
            Collection::init($this->input)->where('a', '<', 70)->where('a', '<', 30)->all()
        );

        $this->assertEquals(
            [                
                ['a' => 20, 'b' => 20],
                ['a' => 20, 'b' => 2],
                ['a' => 20, 'b' => 59],
            ],
            Collection::init($this->input)->where('a', '==', 20)->all()
        );

        $this->assertEquals(
            [                
                ['a' => 20, 'b' => 20],
            ],
            Collection::init($this->input)->where('a', '==', 20)->where('b', '==', 20)->all()
        );

        $this->assertEquals(
            [                
                ['a' => 20, 'b' => 20],
                ['a' => 20, 'b' => 2],
                ['a' => 20, 'b' => 59],
            ],
            Collection::init($this->input)->where('a', '===', 20)->all()
        );

        $this->assertEquals(
            [                
                ['a' => 20, 'b' => 20],
            ],
            Collection::init($this->input)->where('a', '===', 20)->where('b', '===', 20)->all()
        );

        $this->assertEquals(
            $this->input,
            Collection::init($this->input)->where('a', '>=', 20)->all()
        );

        $this->assertEquals(
            [
                ['a' => 30, 'b' => 5],
                ['a' => 77, 'b' => 66]
            ],
            Collection::init($this->input)->where('a', '>=', 20)->where('a', '>=', 30)->all()
        );

        $this->assertEquals(
            [
                ['a' => 20, 'b' => 20],
                ['a' => 20, 'b' => 2],
                ['a' => 20, 'b' => 59],
            ],
            Collection::init($this->input)->where('a', '<=', 20)->all()
        );

        $this->assertEquals(
            [
                ['a' => 20, 'b' => 20],
                ['a' => 25, 'b' => 25],
                ['a' => 30, 'b' => 5],
                ['a' => 20, 'b' => 2],
                ['a' => 20, 'b' => 59],
            ],
            Collection::init($this->input)->where('a', '<=', 68)->where('a', '<=', 30)->all()
        );

        $this->assertEquals(
            [
                ['a' => 25, 'b' => 25],
                ['a' => 30, 'b' => 5],
                ['a' => 77, 'b' => 66]
            ],
            Collection::init($this->input)->where('a', '<>', 20)->all()
        );

        $this->assertEquals(
            [
                ['a' => 25, 'b' => 25],
                ['a' => 77, 'b' => 66]
            ],
            Collection::init($this->input)->where('a', '<>', 20)->where('a', '<>', 30)->all()
        );
    }

    public function testIsArrayMatchesAfterWhereMethodWithNullArguments(): void
    {
        $this->assertEquals(
            $this->input,
            Collection::init($this->input)->where('a', '<>', null)->all()
        );
        
        $this->assertEquals(
            $this->input,
            Collection::init($this->input)->where('a', '!==', null)->all()
        );

        $this->assertEquals(
            [],
            Collection::init($this->input)->where('a', null)->all()
        );
    }
    
    public function testIsArrayMatchesAfterWhereMethodWithArrayWith2Arguments(): void
    {
        $this->assertEquals(
            [
                ['a' => 20, 'b' => 20],
                ['a' => 20, 'b' => 2],
                ['a' => 20, 'b' => 59],
            ],
            Collection::init($this->input)->where([
                ['a', 20]
            ])->all()
        );
        
        $this->assertEquals(
            [
                ['a' => 20, 'b' => 2]
            ],
            Collection::init($this->input)->where([
                ['a', 20],
                ['b', 2]
            ])->all()
        );
        
        $this->assertEquals(
            [],
            Collection::init($this->input)->where([
                ['a', 20],
                ['b', 2],
                ['c', 10]
            ])->all()
        );
    }

    public function testIsArrayMatchesAfterWhereMethodWithArrayWith3Arguments(): void
    {  
        $this->assertEquals(
            [],
            Collection::init($this->input)->where([
                ['a', '<', 30],
                ['b', '<=', 58],
                ['c', '>', 20]
            ])->all()
        );
        
        $this->assertEquals(
            [
                ['a' => 25, 'b' => 25],
                ['a' => 30, 'b' => 5]
            ],
            Collection::init($this->input)->where([
                ['a', '>', 20],
                ['b', '<=', 25]
            ])->all()
        );
        
        $this->assertEquals(
            [
                ['a' => 20, 'b' => 20],
                ['a' => 20, 'b' => 59],
            ],
            Collection::init($this->input)->where([
                ['a', '===', 20],
                ['b', '!==', 2]
            ])->all()
        );
    }

    public function testIsArrayMatchesAfterWhereMethodWithArrayWithMixedArguments(): void
    {
        $this->assertEquals(
            [
                ['a' => 20, 'b' => 20],
                ['a' => 20, 'b' => 2]
            ],
            Collection::init($this->input)->where([
                ['a', 20],
                ['b', '<=', 20]
            ])->all()
        );
        
        $this->assertEquals(
            [
                ['a' => 77, 'b' => 66]
            ],
            Collection::init($this->input)->where([
                ['a', '>', 20],
                ['b', 66]
            ])->all()
        );
    }

    public function testIsArrayMatchesAfterWhereInMethod(): void
    {
        $this->assertEquals(
            [
                ['a' => 20, 'b' => 20],
                ['a' => 25, 'b' => 25],
                ['a' => 20, 'b' => 2],
                ['a' => 20, 'b' => 59],
            ],
            Collection::init($this->input)->whereIn('a', [20, 25])->all()
        );
    }

    public function testIsArrayEmptyIfNoValuesInWhereInMethodMatch(): void
    {
        $this->assertEquals(
            [],
            Collection::init($this->input)->whereIn('a', [4, 6, 8])->all()
        );
    }

    public function testIsArrayEmptyIfKeyDoesntExistInWhereInMethod(): void
    {
        $this->assertEquals(
            [],
            Collection::init($this->input)->whereIn('c', [20, 25])->all()
        );
    }

    public function testIsArrayWithGivenValueExistAndSortedByKeyThatDoesntExist(): void
    {
        $this->assertEquals(
            [
                ['a' => 20, 'b' => 20],
                ['a' => 20, 'b' => 2],
                ['a' => 20, 'b' => 59]
            ],
            Collection::init($this->input)->where('a', 20)->sort('c', 'asc')->all()
        );
    }
    
    public function testIsArrayWithGivenValueDoesntExistAndSortedByKeyThatDoesntExist(): void
    {
        $this->assertEquals(
            [],
            Collection::init($this->input)->where('с', 20)->sort('c', 'asc')->all()
        );
    }
    
    public function testIsArrayWithGivenValueExistsAndSortedByKeyThatExistsAsc(): void
    {
        $this->assertEquals(
            [
                ['a' => 20, 'b' => 59],
                ['a' => 20, 'b' => 20],
                ['a' => 20, 'b' => 2]
            ],
            Collection::init($this->input)->where('a', 20)->sort('b', 'asc')->all()
        );
    }

    public function testIsArrayWithGivenValueExistsAndSortedByKeyThatExistsDesc(): void
    {
        $this->assertEquals(
            [
                ['a' => 20, 'b' => 2],
                ['a' => 20, 'b' => 20],
                ['a' => 20, 'b' => 59]
            ],
            Collection::init($this->input)->where('a', 20)->sort('b', 'desc')->all()
        );
    }

    public function testIsArrayIsCorrectAfterRejecting(): void
    {
        $this->assertEquals(
            $this->input,
            Collection::init($this->input)->reject(fn (array $elem): bool => $elem['a'] === 10)->all()
        );

        $this->assertEquals(
            [
                ['a' => 30, 'b' => 5],
                ['a' => 77, 'b' => 66]
            ],
            Collection::init($this->input)->where('a', '>', 20)->reject(fn (array $elem): bool => $elem['a'] === 25)->all()
        );
    }

    public function testCheckIfCountOfCOllectionIsRight(): void
    {
        $this->assertEquals(
            6,
            Collection::init($this->input)->count()
        );

        $this->assertEquals(
            2,
            Collection::init($this->input)->where('a', '>', 20)->reject(fn (array $elem): bool => $elem['a'] === 25)->count()
        );
    }

    public function testCollectionInitializationThroughStaticMethod(): void
    {
        $this->assertEquals(
            $this->input,
            Collection::init($this->input)->all()
        );
    }

    public function testFilter(): void
    {
        $this->assertEquals(
            [
                ['a' => 77, 'b' => 66]
            ],
            Collection::init($this->input)->where('a', '>', 25)->filter(fn (array $element): bool => $element['a'] + $element['b'] > 40)->all()
        );
    }

    public function testGetFirstElement(): void
    {
        $this->assertEquals(
            ['a' => 30, 'b' => 5],
            Collection::init($this->input)->where('a', '>', 25)->first()
        );
    }

    public function testGetFirstElementInEmptyCollection(): void
    {
        $this->assertEquals(
            null,
            Collection::init([])->where('a', '>', 25)->first()
        );
    }

    public function testGetLastElement(): void
    {
        $this->assertEquals(
            ['a' => 77, 'b' => 66],
            Collection::init($this->input)->where('a', '>', 25)->last()
        );
    }
    public function testGetLastElementInEmptyCollection(): void
    {
        $this->assertEquals(
            null,
            Collection::init([])->where('a', '>', 25)->last()
        );
    }
}