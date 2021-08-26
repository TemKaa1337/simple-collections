<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Collections\ArrayCollection;

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

    public function testIsArrayMatchesAfterWhereMethod(): void
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

    public function testIsArrayWithGivenValueExistsAndSortedByCallback(): void
    {
        $this->assertEquals(
            [
                ['a' => 20, 'b' => 59]
            ],
            (new ArrayCollection($this->input))->where('a', 20)->sortBy(fn ($element) => $element['b'] > 30)->all()
        );
    }
    // public function test(): void
    // {
    //     (new ArrayCollection($array))->where('a', 20)->sortBy(fn ($a, $b) => $a['c'] > $b['c'])->all()
    // }
}