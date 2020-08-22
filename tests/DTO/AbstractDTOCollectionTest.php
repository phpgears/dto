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
use Gears\DTO\Tests\Stub\AbstractDTOCollectionStub;
use PHPUnit\Framework\TestCase;

/**
 * AbstractDTOCollection test.
 */
class AbstractDTOCollectionTest extends TestCase
{
    public function testNoSerialization(): void
    {
        $this->expectException(DTOException::class);
        $this->expectExceptionMessage(
            'DTO "Gears\DTO\Tests\Stub\AbstractDTOCollectionStub" cannot be serialized'
        );

        \serialize(AbstractDTOCollectionStub::fromElements([]));
    }

    public function testNoDeserialization(): void
    {
        $this->expectException(DTOException::class);
        $this->expectExceptionMessage(
            'DTO "Gears\DTO\Tests\Stub\AbstractDTOCollectionStub" cannot be unserialized'
        );

        \unserialize('O:46:"Gears\DTO\Tests\Stub\AbstractDTOCollectionStub":0:{}');
    }
}
