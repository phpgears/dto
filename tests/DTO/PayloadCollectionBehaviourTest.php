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
use Gears\DTO\Tests\Stub\AbstractDTOCollectionStub;
use Gears\DTO\Tests\Stub\AbstractDTOStub;
use Gears\DTO\Tests\Stub\PayloadCollectionBehaviourInvalidTypeStub;
use Gears\DTO\Tests\Stub\PayloadCollectionBehaviourStub;
use PHPUnit\Framework\TestCase;

/**
 * PayloadCollectionBehaviour test.
 */
class PayloadCollectionBehaviourTest extends TestCase
{
    public function testInvalidType(): void
    {
        $this->expectException(InvalidCollectionTypeException::class);
        $this->expectExceptionMessageRegExp(
            '/^Allowed class type for ".+" should be a ".+", ".+\\InvalidParameterException" given\.$/'
        );

        PayloadCollectionBehaviourInvalidTypeStub::fromElements([]);
    }

    public function testPayload(): void
    {
        $elements = [
            AbstractDTOStub::fromArray([]),
            AbstractDTOStub::fromArray([]),
        ];

        $stub = PayloadCollectionBehaviourStub::fromElements($elements);

        static::assertSame($elements, \iterator_to_array($stub->get('elements')));
        static::assertSame($elements, \iterator_to_array($stub->getElements()));
        static::assertSame($elements, \iterator_to_array($stub->getIterator()));
    }

    public function testInvalidParameter(): void
    {
        $this->expectException(InvalidParameterException::class);
        $this->expectExceptionMessageRegExp(
            '/^Only "elements" parameter allowed in ".+", "invalid" given\.$/'
        );

        PayloadCollectionBehaviourStub::fromInvalid('invalid', []);
    }

    public function testInvalidValue(): void
    {
        $this->expectException(InvalidParameterException::class);
        $this->expectExceptionMessage('"elements" parameter should be an iterable, "string" given.');

        PayloadCollectionBehaviourStub::fromInvalid('elements', 'invalid');
    }

    public function testInvalidElement(): void
    {
        $this->expectException(InvalidParameterException::class);
        $this->expectExceptionMessageRegExp('/^All elements of ".+" should be instances of ".+", "stdClass" given\.$/');

        AbstractDTOCollectionStub::fromElements([AbstractDTOStub::fromArray([]), new \stdClass()]);
    }
}
