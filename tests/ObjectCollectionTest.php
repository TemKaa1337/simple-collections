<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SimpleCollections\Collections\ObjectCollection;
use SimpleCollections\Exceptions\InvalidOperatorException;


final class ObjectCollectionTest extends TestCase
{
    // from a = 20, b = 80
    // to   a = 45, b = 55
    protected array $input;

    public function setInput(): void
    {
        $result = [];
        $a = 20;
        $b = 80;

        for ($i = 0; $i < 6; $i ++) {
            $std = new stdClass;
            $std->a = $a + $i * 5;
            $std->b = $b - $i * 5;

            $result[] = $std;
        }

        $this->input = $result;
    }

    public function testIsCollectionEmptyWhenCollectionIsEmpty(): void
    {
        $this->setInput();
        $this->assertEquals(
            true,
            (new ObjectCollection([]))->isEmpty()
        );
    }

    public function testIsCollectionEmptyWhenCollectionIsNotEmpty(): void
    {
        $this->setInput();
        $this->assertEquals(
            false,
            (new ObjectCollection($this->input))->isEmpty()
        );
    }

    public function testIsArrayMatchesAfterWhereMethodWith2Arguments(): void
    {
        $this->setInput();
        $this->assertEquals(
            [
                $this->input[0]
            ],
            (new ObjectCollection($this->input))->where('a', 20)->all()
        );
    }

    public function testIsArrayMatchesDefaultIfGivenKeyDoesntExist(): void
    {
        $this->setInput();
        $this->assertEquals(
            [],
            (new ObjectCollection($this->input))->where('c', 20)->all()
        );
    }

    public function testIvalidOperandEquals(): void
    {
        $this->setInput();
        $this->expectException(InvalidOperatorException::class);
        (new ObjectCollection($this->input))->where('a', '=', 10)->all();
    }

    public function testInvalidOperatorNotEquals(): void
    {
        $this->setInput();
        $this->expectException(InvalidOperatorException::class);
        (new ObjectCollection($this->input))->where('a', '!=', 20)->where('a', '!=', 30)->all();
    }

    public function testIsArrayMatchesAfterWhereMethodWith3Arguments(): void
    {
        $this->setInput();
        $this->assertEquals(
            [
                $this->input[1],
                $this->input[2],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            (new ObjectCollection($this->input))->where('a', '>', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[1],
                $this->input[2],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            (new ObjectCollection($this->input))->where('a', '>', 10)->where('a', '>', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[1],
                $this->input[2],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            (new ObjectCollection($this->input))->where('a', '!==', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[1],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            (new ObjectCollection($this->input))->where('a', '!==', 20)->where('a', '!==', 30)->all()
        );

        $this->assertEquals(
            [],
            (new ObjectCollection($this->input))->where('a', '<', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[0],
                $this->input[1],
            ],
            (new ObjectCollection($this->input))->where('a', '<', 70)->where('a', '<', 30)->all()
        );

        $this->assertEquals(
            [                
                $this->input[0],
            ],
            (new ObjectCollection($this->input))->where('a', '==', 20)->all()
        );

        $this->assertEquals(
            [],
            (new ObjectCollection($this->input))->where('a', '==', 20)->where('b', '==', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[0],
            ],
            (new ObjectCollection($this->input))->where('a', '===', 20)->all()
        );

        $this->assertEquals(
            [],
            (new ObjectCollection($this->input))->where('a', '===', 20)->where('b', '===', 20)->all()
        );

        $this->assertEquals(
            $this->input,
            (new ObjectCollection($this->input))->where('a', '>=', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[2],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            (new ObjectCollection($this->input))->where('a', '>=', 20)->where('a', '>=', 30)->all()
        );

        $this->assertEquals(
            [
                $this->input[0]
            ],
            (new ObjectCollection($this->input))->where('a', '<=', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[0],
                $this->input[1],
                $this->input[2]
            ],
            (new ObjectCollection($this->input))->where('a', '<=', 68)->where('a', '<=', 30)->all()
        );

        $this->assertEquals(
            [
                $this->input[1],
                $this->input[2],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            (new ObjectCollection($this->input))->where('a', '<>', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[1],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            (new ObjectCollection($this->input))->where('a', '<>', 20)->where('a', '<>', 30)->all()
        );
    }

    public function testIsArrayMatchesAfterWhereMethodWithNullArguments(): void
    {
        $this->setInput();
        $this->assertEquals(
            $this->input,
            (new ObjectCollection($this->input))->where('a', '<>', null)->all()
        );
        
        $this->assertEquals(
            $this->input,
            (new ObjectCollection($this->input))->where('a', '!==', null)->all()
        );

        $this->assertEquals(
            [],
            (new ObjectCollection($this->input))->where('a', null)->all()
        );
    }
    
    public function testIsArrayMatchesAfterWhereMethodWithArrayWith2Arguments(): void
    {
        $this->setInput();
        $this->assertEquals(
            [
                $this->input[0]
            ],
            (new ObjectCollection($this->input))->where([
                ['a', 20]
            ])->all()
        );
        
        $this->assertEquals(
            [],
            (new ObjectCollection($this->input))->where([
                ['a', 20],
                ['b', 2]
            ])->all()
        );
        
        $this->assertEquals(
            [],
            (new ObjectCollection($this->input))->where([
                ['a', 20],
                ['b', 2],
                ['c', 10]
            ])->all()
        );
    }

    public function testIsArrayMatchesAfterWhereMethodWithArrayWith3Arguments(): void
    {  
        $this->setInput();
        $this->assertEquals(
            [],
            (new ObjectCollection($this->input))->where([
                ['a', '<', 30],
                ['b', '<=', 58],
                ['c', '>', 20]
            ])->all()
        );
        
        $this->assertEquals(
            [
                $this->input[4],
                $this->input[5],
            ],
            (new ObjectCollection($this->input))->where([
                ['a', '>', 20],
                ['b', '<=', 60]
            ])->all()
        );
        
        $this->assertEquals(
            [
                $this->input[0]
            ],
            (new ObjectCollection($this->input))->where([
                ['a', '===', 20],
                ['b', '!==', 2]
            ])->all()
        );
    }

    public function testIsArrayMatchesAfterWhereMethodWithArrayWithMixedArguments(): void
    {
        $this->setInput();
        $this->assertEquals(
            [],
            (new ObjectCollection($this->input))->where([
                ['a', 20],
                ['b', '<=', 20]
            ])->all()
        );
        
        $this->assertEquals(
            [
                $this->input[5],
            ],
            (new ObjectCollection($this->input))->where([
                ['a', '>', 20],
                ['b', 55]
            ])->all()
        );
    }

    public function testIsArrayMatchesAfterWhereInMethod(): void
    {
        $this->setInput();
        $this->assertEquals(
            [
                $this->input[0],
                $this->input[1],
                $this->input[2]
            ],
            (new ObjectCollection($this->input))->whereIn('a', [20, 25, 30])->all()
        );
    }

    public function testIsArrayEmptyIfNoValuesInWhereInMethodMatch(): void
    {
        $this->setInput();
        $this->assertEquals(
            [],
            (new ObjectCollection($this->input))->whereIn('a', [4, 6, 8])->all()
        );
    }

    public function testIsArrayEmptyIfKeyDoesntExistInWhereInMethod(): void
    {
        $this->setInput();
        $this->assertEquals(
            [],
            (new ObjectCollection($this->input))->whereIn('c', [20, 25])->all()
        );
    }

    public function testIsArrayWithGivenValueExistAndSortedByKeyThatDoesntExist(): void
    {
        $this->setInput();
        $this->assertEquals(
            [
                $this->input[0],
            ],
            (new ObjectCollection($this->input))->where('a', 20)->sort('c', 'asc')->all()
        );
    }
    
    public function testIsArrayWithGivenValueDoesntExistAndSortedByKeyThatDoesntExist(): void
    {
        $this->setInput();
        $this->assertEquals(
            [],
            (new ObjectCollection($this->input))->where('с', 20)->sort('c', 'asc')->all()
        );
    }
    
    public function testIsArrayWithGivenValueExistsAndSortedByKeyThatExistsAsc(): void
    {
        $this->setInput();
        $this->assertEquals(
            [
                $this->input[0],
                $this->input[1],
                $this->input[2]
            ],
            (new ObjectCollection($this->input))->whereIn('a', [20, 25, 30])->sort('b', 'asc')->all()
        );
    }

    public function testIsArrayWithGivenValueExistsAndSortedByKeyThatExistsDesc(): void
    {
        $this->setInput();
        $this->assertEquals(
            [
                $this->input[0]
            ],
            (new ObjectCollection($this->input))->where('a', 20)->sort('b', 'desc')->all()
        );
    }
}