<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Temkaa\Collections\ArrayCollection;
use Temkaa\Exceptions\InvalidOperatorException;

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
            (new ArrayCollection([]))->isEmpty()
        );
    }

    public function testIsCollectionEmptyWhenCollectionIsNotEmpty(): void
    {
        $this->assertEquals(
            false,
            (new ArrayCollection($this->input))->isEmpty()
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
            (new ArrayCollection($this->input))->where('a', 20)->all()
        );
    }

    public function testIsArrayMatchesDefaultIfGivenKeyDoesntExist(): void
    {
        $this->assertEquals(
            [],
            (new ArrayCollection($this->input))->where('c', 20)->all()
        );
    }

    public function testIvalidOperandEquals(): void
    {
        $this->expectException(InvalidOperatorException::class);
        (new ArrayCollection($this->input))->where('a', '=', 10)->all();
    }

    public function testInvalidOperatorNotEquals(): void
    {
        $this->expectException(InvalidOperatorException::class);
        (new ArrayCollection($this->input))->where('a', '!=', 20)->where('a', '!=', 30)->all();
    }

    public function testIsArrayMatchesAfterWhereMethodWith3Arguments(): void
    {
        $this->assertEquals(
            [
                ['a' => 25, 'b' => 25],
                ['a' => 30, 'b' => 5],
                ['a' => 77, 'b' => 66]
            ],
            (new ArrayCollection($this->input))->where('a', '>', 20)->all()
        );

        $this->assertEquals(
            [
                ['a' => 25, 'b' => 25],
                ['a' => 30, 'b' => 5],
                ['a' => 77, 'b' => 66]
            ],
            (new ArrayCollection($this->input))->where('a', '>', 10)->where('a', '>', 20)->all()
        );

        $this->assertEquals(
            [                
                ['a' => 25, 'b' => 25],
                ['a' => 30, 'b' => 5],
                ['a' => 77, 'b' => 66]
            ],
            (new ArrayCollection($this->input))->where('a', '!==', 20)->all()
        );

        $this->assertEquals(
            [                
                ['a' => 25, 'b' => 25],
                ['a' => 77, 'b' => 66]
            ],
            (new ArrayCollection($this->input))->where('a', '!==', 20)->where('a', '!==', 30)->all()
        );

        $this->assertEquals(
            [],
            (new ArrayCollection($this->input))->where('a', '<', 20)->all()
        );

        $this->assertEquals(
            [                
                ['a' => 20, 'b' => 20],
                ['a' => 25, 'b' => 25],
                ['a' => 20, 'b' => 2],
                ['a' => 20, 'b' => 59],
            ],
            (new ArrayCollection($this->input))->where('a', '<', 70)->where('a', '<', 30)->all()
        );

        $this->assertEquals(
            [                
                ['a' => 20, 'b' => 20],
                ['a' => 20, 'b' => 2],
                ['a' => 20, 'b' => 59],
            ],
            (new ArrayCollection($this->input))->where('a', '==', 20)->all()
        );

        $this->assertEquals(
            [                
                ['a' => 20, 'b' => 20],
            ],
            (new ArrayCollection($this->input))->where('a', '==', 20)->where('b', '==', 20)->all()
        );

        $this->assertEquals(
            [                
                ['a' => 20, 'b' => 20],
                ['a' => 20, 'b' => 2],
                ['a' => 20, 'b' => 59],
            ],
            (new ArrayCollection($this->input))->where('a', '===', 20)->all()
        );

        $this->assertEquals(
            [                
                ['a' => 20, 'b' => 20],
            ],
            (new ArrayCollection($this->input))->where('a', '===', 20)->where('b', '===', 20)->all()
        );

        $this->assertEquals(
            $this->input,
            (new ArrayCollection($this->input))->where('a', '>=', 20)->all()
        );

        $this->assertEquals(
            [
                ['a' => 30, 'b' => 5],
                ['a' => 77, 'b' => 66]
            ],
            (new ArrayCollection($this->input))->where('a', '>=', 20)->where('a', '>=', 30)->all()
        );

        $this->assertEquals(
            [
                ['a' => 20, 'b' => 20],
                ['a' => 20, 'b' => 2],
                ['a' => 20, 'b' => 59],
            ],
            (new ArrayCollection($this->input))->where('a', '<=', 20)->all()
        );

        $this->assertEquals(
            [
                ['a' => 20, 'b' => 20],
                ['a' => 25, 'b' => 25],
                ['a' => 30, 'b' => 5],
                ['a' => 20, 'b' => 2],
                ['a' => 20, 'b' => 59],
            ],
            (new ArrayCollection($this->input))->where('a', '<=', 68)->where('a', '<=', 30)->all()
        );

        $this->assertEquals(
            [
                ['a' => 25, 'b' => 25],
                ['a' => 30, 'b' => 5],
                ['a' => 77, 'b' => 66]
            ],
            (new ArrayCollection($this->input))->where('a', '<>', 20)->all()
        );

        $this->assertEquals(
            [
                ['a' => 25, 'b' => 25],
                ['a' => 77, 'b' => 66]
            ],
            (new ArrayCollection($this->input))->where('a', '<>', 20)->where('a', '<>', 30)->all()
        );
    }

    public function testIsArrayMatchesAfterWhereMethodWithNullArguments(): void
    {
        $this->assertEquals(
            $this->input,
            (new ArrayCollection($this->input))->where('a', '<>', null)->all()
        );
        
        $this->assertEquals(
            $this->input,
            (new ArrayCollection($this->input))->where('a', '!==', null)->all()
        );

        $this->assertEquals(
            [],
            (new ArrayCollection($this->input))->where('a', null)->all()
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
            (new ArrayCollection($this->input))->where([
                ['a', 20]
            ])->all()
        );
        
        $this->assertEquals(
            [
                ['a' => 20, 'b' => 2]
            ],
            (new ArrayCollection($this->input))->where([
                ['a', 20],
                ['b', 2]
            ])->all()
        );
        
        $this->assertEquals(
            [],
            (new ArrayCollection($this->input))->where([
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
            (new ArrayCollection($this->input))->where([
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
            (new ArrayCollection($this->input))->where([
                ['a', '>', 20],
                ['b', '<=', 25]
            ])->all()
        );
        
        $this->assertEquals(
            [
                ['a' => 20, 'b' => 20],
                ['a' => 20, 'b' => 59],
            ],
            (new ArrayCollection($this->input))->where([
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
            (new ArrayCollection($this->input))->where([
                ['a', 20],
                ['b', '<=', 20]
            ])->all()
        );
        
        $this->assertEquals(
            [
                ['a' => 77, 'b' => 66]
            ],
            (new ArrayCollection($this->input))->where([
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
            (new ArrayCollection($this->input))->whereIn('a', [20, 25])->all()
        );
    }

    public function testIsArrayEmptyIfNoValuesInWhereInMethodMatch(): void
    {
        $this->assertEquals(
            [],
            (new ArrayCollection($this->input))->whereIn('a', [4, 6, 8])->all()
        );
    }

    public function testIsArrayEmptyIfKeyDoesntExistInWhereInMethod(): void
    {
        $this->assertEquals(
            [],
            (new ArrayCollection($this->input))->whereIn('c', [20, 25])->all()
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
            (new ArrayCollection($this->input))->where('a', 20)->sort('c', 'asc')->all()
        );
    }
    
    public function testIsArrayWithGivenValueDoesntExistAndSortedByKeyThatDoesntExist(): void
    {
        $this->assertEquals(
            [],
            (new ArrayCollection($this->input))->where('с', 20)->sort('c', 'asc')->all()
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
            (new ArrayCollection($this->input))->where('a', 20)->sort('b', 'asc')->all()
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
            (new ArrayCollection($this->input))->where('a', 20)->sort('b', 'desc')->all()
        );
    }
}