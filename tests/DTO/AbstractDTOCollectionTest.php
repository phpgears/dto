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

use Gears\DTO\Tests\Stub\AbstractDTOCollectionStub;
use Gears\DTO\Tests\Stub\AbstractDTOStub;
use PHPUnit\Framework\TestCase;

/**
 * AbstractDTOCollection test.
 */
class AbstractDTOCollectionTest extends TestCase
{
    public function testPayload(): void
    {
        $elements = [AbstractDTOStub::fromArray([])];
        $stub = AbstractDTOCollectionStub::fromElements($elements);

        static::assertSame($elements, \iterator_to_array($stub->get('elements')));
    }
}
