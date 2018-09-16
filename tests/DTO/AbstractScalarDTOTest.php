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

use Gears\DTO\Tests\Stub\AbstractScalarDTOStub;
use PHPUnit\Framework\TestCase;

/**
 * AbstractScalarDTO test.
 */
class AbstractScalarDTOTest extends TestCase
{
    public function testScalar(): void
    {
        $stub = AbstractScalarDTOStub::fromArray(['parameter' => 'serializable']);

        $this->assertSame('serializable', $stub->get('parameter'));
    }

    public function testScalarDTO(): void
    {
        $stub = AbstractScalarDTOStub::fromArray(['parameter' => 'serializable']);

        $this->assertSame('serializable', $stub->get('parameter'));
    }

    /**
     * @expectedException \Gears\DTO\Exception\InvalidScalarParameterException
     * @expectedExceptionMessageRegExp /Class .+ can only accept scalar payload parameters, stdClass given/
     */
    public function testNotScalar(): void
    {
        AbstractScalarDTOStub::fromArray(['parameter' => new \stdClass()]);
    }
}
