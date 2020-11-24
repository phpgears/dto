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
use Gears\DTO\Tests\Stub\ScalarPayloadBehaviourStub;
use PHPUnit\Framework\TestCase;

/**
 * ScalarPayloadBehaviour trait test.
 */
class ScalarPayloadBehaviourTest extends TestCase
{
    public function testNotScalar(): void
    {
        $this->expectException(InvalidScalarParameterException::class);
        $this->expectExceptionMessageRegExp(
            '/^Class ".+" can only accept scalar payload parameters, "stdClass" given\.$/'
        );

        new ScalarPayloadBehaviourStub(['parameter' => new \stdClass()]);
    }

    public function testDirectPayload(): void
    {
        $stub = new ScalarPayloadBehaviourStub([
            'parameter' => [100],
            'value' => 'myValue',
        ]);

        static::assertSame([100], $stub->get('parameter'));
        static::assertSame(['parameter' => [100], 'value' => 'myValue'], $stub->getPayload());
    }
}
