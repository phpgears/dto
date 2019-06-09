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

use Gears\DTO\Tests\Stub\AbstractScalarDTOCollectionStub;
use Gears\DTO\Tests\Stub\AbstractScalarDTOStub;
use PHPUnit\Framework\TestCase;

/**
 * AbstractScalarDTO test.
 */
class AbstractScalarDTOTest extends TestCase
{
    /**
     * @expectedException \Gears\DTO\Exception\InvalidScalarParameterException
     * @expectedExceptionMessageRegExp /Class .+ can only accept scalar payload parameters, stdClass given/
     */
    public function testNotScalar(): void
    {
        AbstractScalarDTOStub::fromArray(['parameter' => new \stdClass()]);
    }

    public function testCreation(): void
    {
        $stub = AbstractScalarDTOStub::fromArray([
            'parameter' => 100,
            'object' => AbstractScalarDTOStub::fromArray([]),
        ]);

        $this->assertSame(100, $stub->get('parameter'));
        $this->assertSame(100, $stub->getParameter());
    }

    public function testAcceptDTO(): void
    {
        $stub = AbstractScalarDTOStub::fromArray([
            'object' => AbstractScalarDTOStub::fromArray([]),
        ]);

        $this->assertInstanceOf(AbstractScalarDTOStub::class, $stub->getObject());
    }

    public function testAcceptDTOCollection(): void
    {
        $stub = AbstractScalarDTOStub::fromArray([
            'collection' => AbstractScalarDTOCollectionStub::fromElements([AbstractScalarDTOStub::fromArray([])]),
        ]);

        $this->assertCount(1, $stub->getCollection());
        $this->assertInstanceOf(AbstractScalarDTOStub::class, $stub->getCollection()[0]);
    }
}
