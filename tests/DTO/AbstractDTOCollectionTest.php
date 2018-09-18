<?php

/*
 * dto (https://github.com/phpgears/dto).
 * General purpose immutable Data Transfer Objects for PHP.
 *
 * @license MIT
 * @link https://github.com/phpgears/dto
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

declare(strict_types=1);

namespace Gears\DTO\Tests;

use Gears\DTO\Tests\Stub\AbstractDTOCollectionInvalidTypeStub;
use Gears\DTO\Tests\Stub\AbstractDTOCollectionStub;
use Gears\DTO\Tests\Stub\AbstractDTOStub;
use PHPUnit\Framework\TestCase;

/**
 * AbstractDTOCollection test.
 */
class AbstractDTOCollectionTest extends TestCase
{
    /**
     * @expectedException \Gears\DTO\Exception\InvalidCollectionTypeException
     * @expectedExceptionMessageRegExp /^Allowed class type for .+ should be a .+, .+\\InvalidParameterException given$/
     */
    public function testInvalidType(): void
    {
        AbstractDTOCollectionInvalidTypeStub::fromElements([]);
    }

    /**
     * @expectedException \Gears\DTO\Exception\InvalidParameterException
     * @expectedExceptionMessageRegExp /^All elements of .+ should be instances of .+, stdClass given$/
     */
    public function testInvalidElement(): void
    {
        AbstractDTOCollectionStub::fromElements([AbstractDTOStub::fromArray([]), new \stdClass()]);
    }

    public function testCreation(): void
    {
        $elements = [
            AbstractDTOStub::fromArray([]),
            AbstractDTOStub::fromArray([]),
        ];

        $stub = AbstractDTOCollectionStub::fromElements($elements);

        $this->assertTrue($stub->has('elements'));
        $this->assertSame($elements, \iterator_to_array($stub->get('elements')));
        $this->assertSame($elements, \iterator_to_array($stub->getElements()));
        $this->assertSame($elements, \iterator_to_array($stub->getIterator()));
    }
}
