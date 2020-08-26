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
    public function testSerialization(): void
    {
        $stub = AbstractScalarDTOStub::fromArray(['parameter' => 100]);

        $serialized = \version_compare(\PHP_VERSION, '7.4.0') >= 0
            ? 'O:42:"Gears\DTO\Tests\Stub\AbstractScalarDTOStub":1:{s:7:"payload";a:1:{s:9:"parameter";i:100;}}'
            : 'C:42:"Gears\DTO\Tests\Stub\AbstractScalarDTOStub":28:{a:1:{s:9:"parameter";i:100;}}';

        static::assertSame($serialized, \serialize($stub));
        static::assertSame(1000, (\unserialize($serialized))->getParameter());
    }
}
