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
}
