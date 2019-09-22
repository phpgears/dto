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

use Gears\DTO\Exception\InvalidScalarParameterException;
use Gears\DTO\Tests\Stub\AbstractScalarDTOCollectionStub;
use Gears\DTO\Tests\Stub\AbstractScalarDTOStub;
use PHPUnit\Framework\TestCase;

/**
 * AbstractScalarDTO test.
 */
class AbstractScalarDTOTest extends TestCase
{
    public function testNotScalar(): void
    {
        $this->expectException(InvalidScalarParameterException::class);
        $this->expectExceptionMessageRegExp(
            '/^Class ".+" can only accept scalar payload parameters, "stdClass" given$/'
        );

        AbstractScalarDTOStub::fromArray(['parameter' => new \stdClass()]);
    }

    public function testCreation(): void
    {
        $stub = AbstractScalarDTOStub::fromArray([
            'parameter' => 100,
            'object' => AbstractScalarDTOStub::fromArray([]),
        ]);

        static::assertSame(100, $stub->get('parameter'));
        static::assertSame(100, $stub->getParameter());
    }

    public function testAcceptDTO(): void
    {
        $stub = AbstractScalarDTOStub::fromArray([
            'object' => AbstractScalarDTOStub::fromArray([]),
        ]);

        static::assertInstanceOf(AbstractScalarDTOStub::class, $stub->getObject());
    }

    public function testAcceptDTOCollection(): void
    {
        $stub = AbstractScalarDTOStub::fromArray([
            'collection' => AbstractScalarDTOCollectionStub::fromElements([AbstractScalarDTOStub::fromArray([])]),
        ]);

        static::assertCount(1, $stub->getCollection());
        static::assertInstanceOf(AbstractScalarDTOStub::class, $stub->getCollection()[0]);
    }
}
