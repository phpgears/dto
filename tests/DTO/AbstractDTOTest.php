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

use Gears\DTO\Exception\DTOException;
use Gears\DTO\Tests\Stub\AbstractDTOStub;
use PHPUnit\Framework\TestCase;

/**
 * AbstractDTO test.
 */
class AbstractDTOTest extends TestCase
{
    public function testCreation(): void
    {
        $stub = AbstractDTOStub::fromArray(['parameter' => 100]);

        static::assertSame(100, $stub->get('parameter'));
        static::assertSame(100, $stub->getParameter());
    }

    public function testAcceptDTO(): void
    {
        $stub = AbstractDTOStub::fromArray([
            'object' => AbstractDTOStub::fromArray([]),
        ]);

        static::assertInstanceOf(AbstractDTOStub::class, $stub->getObject());
    }

    public function testNoSerialization(): void
    {
        $this->expectException(DTOException::class);
        $this->expectExceptionMessage('DTO "Gears\DTO\Tests\Stub\AbstractDTOStub" cannot be serialized');

        \serialize(AbstractDTOStub::fromArray([]));
    }

    public function testNoDeserialization(): void
    {
        $this->expectException(DTOException::class);
        $this->expectExceptionMessage('DTO "Gears\DTO\Tests\Stub\AbstractDTOStub" cannot be unserialized');

        \unserialize('O:36:"Gears\DTO\Tests\Stub\AbstractDTOStub":0:{}');
    }
}
