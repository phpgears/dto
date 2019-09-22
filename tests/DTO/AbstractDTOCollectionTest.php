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

use Gears\DTO\Exception\InvalidCollectionTypeException;
use Gears\DTO\Exception\InvalidParameterException;
use Gears\DTO\Tests\Stub\AbstractDTOCollectionInvalidTypeStub;
use Gears\DTO\Tests\Stub\AbstractDTOCollectionStub;
use Gears\DTO\Tests\Stub\AbstractDTOStub;
use PHPUnit\Framework\TestCase;

/**
 * AbstractDTOCollection test.
 */
class AbstractDTOCollectionTest extends TestCase
{
    public function testInvalidType(): void
    {
        $this->expectException(InvalidCollectionTypeException::class);
        $this->expectExceptionMessageRegExp(
            '/^Allowed class type for ".+" should be a ".+", ".+\\InvalidParameterException" given$/'
        );

        AbstractDTOCollectionInvalidTypeStub::fromElements([]);
    }

    public function testInvalidElement(): void
    {
        $this->expectException(InvalidParameterException::class);
        $this->expectExceptionMessageRegExp('/^All elements of ".+" should be instances of ".+", "stdClass" given$/');

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
