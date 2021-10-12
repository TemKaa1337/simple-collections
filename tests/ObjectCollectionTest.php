<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SimpleCollections\Collections\Collection;
use SimpleCollections\Exceptions\InvalidOperatorException;
use SimpleCollections\Exceptions\InvalidInputFormatException;


final class ObjectCollectionTest extends TestCase
{
    // from a = 20, b = 80
    // to   a = 45, b = 55
    protected array $input;

    public function setStaticInput(): void
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

    public function setDynamicInput(): void
    {
        $result = [];
        $a = 20;
        $b = 80;

        for ($i = 0; $i < 6; $i ++) {
            $class = new class {
                protected array $fields = [];

                public function __set($field, $value)
                {
                    $this->fields[$field] = $value;
                }

                public function __get($field)
                {
                    return $this->fields[$field] ?? null;
                }
            };

            $class->a = $a + $i * 5;
            $class->b = $b - $i * 5;

            $result[] = $class;
        }

        $this->input = $result;
    }

    // static properties

    public function testIsCollectionEmptyWhenCollectionIsEmptyWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            true,
            Collection::init([])->isEmpty()
        );
    }

    public function testIsCollectionEmptyWhenCollectionIsNotEmptyWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            false,
            Collection::init($this->input)->isEmpty()
        );
    }

    public function testIsArrayMatchesAfterWhereMethodWith2ArgumentsWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            [
                $this->input[0]
            ],
            Collection::init($this->input)->where('a', 20)->all()
        );
    }

    public function testIsArrayMatchesDefaultIfGivenKeyDoesntExistWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            [],
            Collection::init($this->input)->where('c', 20)->all()
        );
    }

    public function testIvalidOperandEqualsWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->expectException(InvalidOperatorException::class);
        Collection::init($this->input)->where('a', '=', 10)->all();
    }

    public function testInvalidOperatorNotEqualsWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->expectException(InvalidOperatorException::class);
        Collection::init($this->input)->where('a', '!=', 20)->where('a', '!=', 30)->all();
    }

    public function testIsArrayMatchesAfterWhereMethodWith3ArgumentsWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            [
                $this->input[1],
                $this->input[2],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            Collection::init($this->input)->where('a', '>', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[1],
                $this->input[2],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            Collection::init($this->input)->where('a', '>', 10)->where('a', '>', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[1],
                $this->input[2],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            Collection::init($this->input)->where('a', '!==', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[1],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            Collection::init($this->input)->where('a', '!==', 20)->where('a', '!==', 30)->all()
        );

        $this->assertEquals(
            [],
            Collection::init($this->input)->where('a', '<', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[0],
                $this->input[1],
            ],
            Collection::init($this->input)->where('a', '<', 70)->where('a', '<', 30)->all()
        );

        $this->assertEquals(
            [                
                $this->input[0],
            ],
            Collection::init($this->input)->where('a', '==', 20)->all()
        );

        $this->assertEquals(
            [],
            Collection::init($this->input)->where('a', '==', 20)->where('b', '==', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[0],
            ],
            Collection::init($this->input)->where('a', '===', 20)->all()
        );

        $this->assertEquals(
            [],
            Collection::init($this->input)->where('a', '===', 20)->where('b', '===', 20)->all()
        );

        $this->assertEquals(
            $this->input,
            Collection::init($this->input)->where('a', '>=', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[2],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            Collection::init($this->input)->where('a', '>=', 20)->where('a', '>=', 30)->all()
        );

        $this->assertEquals(
            [
                $this->input[0]
            ],
            Collection::init($this->input)->where('a', '<=', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[0],
                $this->input[1],
                $this->input[2]
            ],
            Collection::init($this->input)->where('a', '<=', 68)->where('a', '<=', 30)->all()
        );

        $this->assertEquals(
            [
                $this->input[1],
                $this->input[2],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            Collection::init($this->input)->where('a', '<>', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[1],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            Collection::init($this->input)->where('a', '<>', 20)->where('a', '<>', 30)->all()
        );
    }

    public function testIsArrayMatchesAfterWhereMethodWithNullArguments(): void
    {
        $this->setStaticInput();
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
    
    public function testIsArrayMatchesAfterWhereMethodWithArrayWith2ArgumentsWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            [
                $this->input[0]
            ],
            Collection::init($this->input)->where([
                ['a', 20]
            ])->all()
        );
        
        $this->assertEquals(
            [],
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

    public function testIsArrayMatchesAfterWhereMethodWithArrayWith3ArgumentsWithStaticProps(): void
    {  
        $this->setStaticInput();
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
                $this->input[4],
                $this->input[5],
            ],
            Collection::init($this->input)->where([
                ['a', '>', 20],
                ['b', '<=', 60]
            ])->all()
        );
        
        $this->assertEquals(
            [
                $this->input[0]
            ],
            Collection::init($this->input)->where([
                ['a', '===', 20],
                ['b', '!==', 2]
            ])->all()
        );
    }

    public function testIsArrayMatchesAfterWhereMethodWithArrayWithMixedArgumentsWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            [],
            Collection::init($this->input)->where([
                ['a', 20],
                ['b', '<=', 20]
            ])->all()
        );
        
        $this->assertEquals(
            [
                $this->input[5],
            ],
            Collection::init($this->input)->where([
                ['a', '>', 20],
                ['b', 55]
            ])->all()
        );
    }

    public function testIsArrayMatchesAfterWhereInMethodWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            [
                $this->input[0],
                $this->input[1],
                $this->input[2]
            ],
            Collection::init($this->input)->whereIn('a', [20, 25, 30])->all()
        );
    }

    public function testIsArrayMatchesAfterWhereNotInMethodWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            [
                $this->input[3],
                $this->input[4],
                $this->input[5]
            ],
            Collection::init($this->input)->whereNotIn('a', [20, 25, 30])->all()
        );
    }

    public function testIsArrayEmptyIfNoValuesInWhereInMethodMatchWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            [],
            Collection::init($this->input)->whereIn('a', [4, 6, 8])->all()
        );
    }

    public function testIsArrayEmptyIfKeyDoesntExistInWhereInMethodWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            [],
            Collection::init($this->input)->whereIn('c', [20, 25])->all()
        );
    }

    public function testIsArrayWithGivenValueExistAndSortedByKeyThatDoesntExistWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            [
                $this->input[0],
            ],
            Collection::init($this->input)->where('a', 20)->sort('c', 'asc')->all()
        );
    }
    
    public function testIsArrayWithGivenValueDoesntExistAndSortedByKeyThatDoesntExistWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            [],
            Collection::init($this->input)->where('с', 20)->sort('c', 'asc')->all()
        );
    }
    
    public function testIsArrayWithGivenValueExistsAndSortedByKeyThatExistsAscWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            [
                $this->input[2],
                $this->input[1],
                $this->input[0]
            ],
            Collection::init($this->input)->whereIn('a', [20, 25, 30])->sort('b', 'asc')->all()
        );
    }

    public function testIsArrayWithGivenValueExistsAndSortedByKeyThatExistsDescWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            [
                $this->input[0]
            ],
            Collection::init($this->input)->where('a', 20)->sort('b', 'desc')->all()
        );
    }

    public function testIsObjectArrayIsCorrectAfterRejectingWithStaticProps(): void
    {
        $this->setStaticInput();

        $this->assertEquals(
            $this->input,
            Collection::init($this->input)->reject(fn (object $elem): bool => $elem->a === 10)->all()
        );

        $this->assertEquals(
            [
                $this->input[2],
                $this->input[3],
                $this->input[4],
                $this->input[5]
            ],
            Collection::init($this->input)->where('a', '>', 20)->reject(fn (object $elem): bool => $elem->a === 25)->all()
        );
    }
    
    public function testCheckIfCountOfCollectionIsRightWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            6,
            Collection::init($this->input)->reject(fn (object $elem): bool => $elem->a === 10)->count()
        );

        $this->assertEquals(
            4,
            Collection::init($this->input)->where('a', '>', 20)->reject(fn (object $elem): bool => $elem->a === 25)->count()
        );
    }
    
    public function testCollectionInitializationThroughStaticMethodWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            $this->input,
            Collection::init($this->input)->all()
        );
    }
    
    public function testFilterWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            [],
            Collection::init($this->input)->where('a', '>', 25)->filter(fn (object $element): bool => ($element->a + $element->b) > 200)->all()
        );
    }

    public function testGetFirstElementWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            $this->input[2],
            Collection::init($this->input)->where('a', '>', 25)->first()
        );
    }

    public function testGetFirstElementInEmptyCollectionWithStaticProps(): void
    {
        $this->assertEquals(
            null,
            Collection::init([])->where('a', '>', 25)->first()
        );
    }

    public function testGetLastElementWithStaticProps(): void
    {
        $this->setStaticInput();
        $this->assertEquals(
            $this->input[5],
            Collection::init($this->input)->where('a', '>', 25)->last()
        );
    }
    public function testGetLastElementInEmptyCollectionWithStaticProps(): void
    {
        $this->assertEquals(
            null,
            Collection::init([])->where('a', '>', 25)->last()
        );
    }

    public function testIncorrentInputDataWithStaticProps(): void
    {
        $this->expectException(InvalidInputFormatException::class);
        Collection::init(['asd', 'bsd'])->all();
    }

    // dynamic properties

    public function testIsCollectionEmptyWhenCollectionIsEmptyWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            true,
            Collection::init([], staticProps: false)->isEmpty()
        );
    }

    public function testIsCollectionEmptyWhenCollectionIsNotEmptyWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            false,
            Collection::init($this->input, staticProps: false)->isEmpty()
        );
    }

    public function testIsArrayMatchesAfterWhereMethodWith2ArgumentsWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            [
                $this->input[0]
            ],
            Collection::init($this->input, staticProps: false)->where('a', 20)->all()
        );
    }

    public function testIsArrayMatchesDefaultIfGivenKeyDoesntExistWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            [],
            Collection::init($this->input, staticProps: false)->where('c', 20)->all()
        );
    }

    public function testIvalidOperandEqualsWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->expectException(InvalidOperatorException::class);
        Collection::init($this->input, staticProps: false)->where('a', '=', 10)->all();
    }

    public function testInvalidOperatorNotEqualsWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->expectException(InvalidOperatorException::class);
        Collection::init($this->input, staticProps: false)->where('a', '!=', 20)->where('a', '!=', 30)->all();
    }

    public function testIsArrayMatchesAfterWhereMethodWith3ArgumentsWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            [
                $this->input[1],
                $this->input[2],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            Collection::init($this->input, staticProps: false)->where('a', '>', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[1],
                $this->input[2],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            Collection::init($this->input, staticProps: false)->where('a', '>', 10)->where('a', '>', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[1],
                $this->input[2],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            Collection::init($this->input, staticProps: false)->where('a', '!==', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[1],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            Collection::init($this->input, staticProps: false)->where('a', '!==', 20)->where('a', '!==', 30)->all()
        );

        $this->assertEquals(
            [],
            Collection::init($this->input, staticProps: false)->where('a', '<', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[0],
                $this->input[1],
            ],
            Collection::init($this->input, staticProps: false)->where('a', '<', 70)->where('a', '<', 30)->all()
        );

        $this->assertEquals(
            [                
                $this->input[0],
            ],
            Collection::init($this->input, staticProps: false)->where('a', '==', 20)->all()
        );

        $this->assertEquals(
            [],
            Collection::init($this->input, staticProps: false)->where('a', '==', 20)->where('b', '==', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[0],
            ],
            Collection::init($this->input, staticProps: false)->where('a', '===', 20)->all()
        );

        $this->assertEquals(
            [],
            Collection::init($this->input, staticProps: false)->where('a', '===', 20)->where('b', '===', 20)->all()
        );

        $this->assertEquals(
            $this->input,
            Collection::init($this->input, staticProps: false)->where('a', '>=', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[2],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            Collection::init($this->input, staticProps: false)->where('a', '>=', 20)->where('a', '>=', 30)->all()
        );

        $this->assertEquals(
            [
                $this->input[0]
            ],
            Collection::init($this->input, staticProps: false)->where('a', '<=', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[0],
                $this->input[1],
                $this->input[2]
            ],
            Collection::init($this->input, staticProps: false)->where('a', '<=', 68)->where('a', '<=', 30)->all()
        );

        $this->assertEquals(
            [
                $this->input[1],
                $this->input[2],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            Collection::init($this->input, staticProps: false)->where('a', '<>', 20)->all()
        );

        $this->assertEquals(
            [
                $this->input[1],
                $this->input[3],
                $this->input[4],
                $this->input[5],
            ],
            Collection::init($this->input, staticProps: false)->where('a', '<>', 20)->where('a', '<>', 30)->all()
        );
    }

    public function testIsArrayMatchesAfterWhereMethodWithNullArgumentsWithDynamicPropsWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            $this->input,
            Collection::init($this->input, staticProps: false)->where('a', '<>', null)->all()
        );
        
        $this->assertEquals(
            $this->input,
            Collection::init($this->input, staticProps: false)->where('a', '!==', null)->all()
        );

        $this->assertEquals(
            [],
            Collection::init($this->input, staticProps: false)->where('a', null)->all()
        );
    }
    
    public function testIsArrayMatchesAfterWhereMethodWithArrayWith2ArgumentsWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            [
                $this->input[0]
            ],
            Collection::init($this->input, staticProps: false)->where([
                ['a', 20]
            ])->all()
        );
        
        $this->assertEquals(
            [],
            Collection::init($this->input, staticProps: false)->where([
                ['a', 20],
                ['b', 2]
            ])->all()
        );
        
        $this->assertEquals(
            [],
            Collection::init($this->input, staticProps: false)->where([
                ['a', 20],
                ['b', 2],
                ['c', 10]
            ])->all()
        );
    }

    public function testIsArrayMatchesAfterWhereMethodWithArrayWith3ArgumentsWithDynamicProps(): void
    {  
        $this->setDynamicInput();
        $this->assertEquals(
            [],
            Collection::init($this->input, staticProps: false)->where([
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
            Collection::init($this->input, staticProps: false)->where([
                ['a', '>', 20],
                ['b', '<=', 60]
            ])->all()
        );
        
        $this->assertEquals(
            [
                $this->input[0]
            ],
            Collection::init($this->input, staticProps: false)->where([
                ['a', '===', 20],
                ['b', '!==', 2]
            ])->all()
        );
    }

    public function testIsArrayMatchesAfterWhereMethodWithArrayWithMixedArgumentsWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            [],
            Collection::init($this->input, staticProps: false)->where([
                ['a', 20],
                ['b', '<=', 20]
            ])->all()
        );
        
        $this->assertEquals(
            [
                $this->input[5],
            ],
            Collection::init($this->input, staticProps: false)->where([
                ['a', '>', 20],
                ['b', 55]
            ])->all()
        );
    }

    public function testIsArrayMatchesAfterWhereInMethodWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            [
                $this->input[0],
                $this->input[1],
                $this->input[2]
            ],
            Collection::init($this->input, staticProps: false)->whereIn('a', [20, 25, 30])->all()
        );
    }

    public function testIsArrayEmptyIfNoValuesInWhereInMethodMatchWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            [],
            Collection::init($this->input, staticProps: false)->whereIn('a', [4, 6, 8])->all()
        );
    }

    public function testIsArrayEmptyIfKeyDoesntExistInWhereInMethodWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            [],
            Collection::init($this->input, staticProps: false)->whereIn('c', [20, 25])->all()
        );
    }

    public function testIsArrayWithGivenValueExistAndSortedByKeyThatDoesntExistWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            [
                $this->input[0],
            ],
            Collection::init($this->input, staticProps: false)->where('a', 20)->sort('c', 'asc')->all()
        );
    }
    
    public function testIsArrayWithGivenValueDoesntExistAndSortedByKeyThatDoesntExistWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            [],
            Collection::init($this->input, staticProps: false)->where('с', 20)->sort('c', 'asc')->all()
        );
    }
    
    public function testIsArrayWithGivenValueExistsAndSortedByKeyThatExistsAscWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            [
                $this->input[2],
                $this->input[1],
                $this->input[0]
            ],
            Collection::init($this->input, staticProps: false)->whereIn('a', [20, 25, 30])->sort('b', 'asc')->all()
        );
    }

    public function testIsArrayWithGivenValueExistsAndSortedByKeyThatExistsDescWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            [
                $this->input[0]
            ],
            Collection::init($this->input, staticProps: false)->where('a', 20)->sort('b', 'desc')->all()
        );
    }

    public function testIsObjectArrayIsCorrectAfterRejectingWithDynamicProps(): void
    {
        $this->setDynamicInput();

        $this->assertEquals(
            $this->input,
            Collection::init($this->input, staticProps: false)->reject(fn (object $elem): bool => $elem->a === 10)->all()
        );

        $this->assertEquals(
            [
                $this->input[2],
                $this->input[3],
                $this->input[4],
                $this->input[5]
            ],
            Collection::init($this->input, staticProps: false)->where('a', '>', 20)->reject(fn (object $elem): bool => $elem->a === 25)->all()
        );
    }
    
    public function testCheckIfCountOfCollectionIsRightWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            6,
            Collection::init($this->input, staticProps: false)->reject(fn (object $elem): bool => $elem->a === 10)->count()
        );

        $this->assertEquals(
            4,
            Collection::init($this->input, staticProps: false)->where('a', '>', 20)->reject(fn (object $elem): bool => $elem->a === 25)->count()
        );
    }
    
    public function testCollectionInitializationThroughStaticMethodWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            $this->input,
            Collection::init($this->input, staticProps: false)->all()
        );
    }
    
    public function testFilterWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            [],
            Collection::init($this->input, staticProps: false)->where('a', '>', 25)->filter(fn (object $element): bool => ($element->a + $element->b) > 200)->all()
        );
    }

    public function testGetFirstElementWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            $this->input[2],
            Collection::init($this->input, staticProps: false)->where('a', '>', 25)->first()
        );
    }

    public function testGetFirstElementInEmptyCollectionWithDynamicProps(): void
    {
        $this->assertEquals(
            null,
            Collection::init([], staticProps: false)->where('a', '>', 25)->first()
        );
    }

    public function testGetLastElementWithDynamicProps(): void
    {
        $this->setDynamicInput();
        $this->assertEquals(
            $this->input[5],
            Collection::init($this->input, staticProps: false)->where('a', '>', 25)->last()
        );
    }

    public function testGetLastElementInEmptyCollectionWithDynamicProps(): void
    {
        $this->assertEquals(
            null,
            Collection::init([], staticProps: false)->where('a', '>', 25)->last()
        );
    }

    public function testWhereClauseForNullField(): void
    {
        $class = new class {
            protected array $fields;

            public function __get(string $field): mixed
            {
                return $this->fields[$field] ?? null;
            }

            public function __set(string $field, mixed $value): void
            {
                $this->fields[$field] = $value;
            }
        };

        $class->a = 20;
        $class->b = null;

        $this->assertEquals(
            $class,
            Collection::init([$class], staticProps: false)->where('a', 20)->where('b', null)->first()
        );
    }

    public function testIncorrentInputDataWithDynamicProps(): void
    {
        $this->expectException(InvalidInputFormatException::class);
        Collection::init(['asd', 'bsd'])->all();
    }
}