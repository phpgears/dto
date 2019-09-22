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

use Gears\DTO\Exception\InvalidMethodCallException;
use Gears\DTO\Exception\InvalidParameterException;
use Gears\DTO\Tests\Stub\PayloadBehaviourStub;
use PHPUnit\Framework\TestCase;

/**
 * PayloadBehaviour trait test.
 */
class PayloadBehaviourTest extends TestCase
{
    public function testDirectPayload(): void
    {
        $stub = new PayloadBehaviourStub(['Parameter' => 100, 'argument' => 'value']);

        static::assertTrue($stub->has('Parameter'));
        static::assertSame(100, $stub->get('Parameter'));
        static::assertSame(100, $stub->getParameter());

        static::assertTrue($stub->has('argument'));
        static::assertSame('value', $stub->get('argument'));
        static::assertSame('value', $stub->getArgument());
    }

    public function testPayloadParsing(): void
    {
        $stub = new PayloadBehaviourStub(['value' => 'myValue']);

        static::assertTrue($stub->has('value'));
        static::assertSame('myvalue', $stub->get('value'));

        static::assertSame(['value' => 'myValue'], $stub->getPayload());
    }

    public function testNonExistentPayload(): void
    {
        $this->expectException(InvalidParameterException::class);
        $this->expectExceptionMessageRegExp('/^Payload parameter "attribute" on ".+" does not exist$/');

        $stub = new PayloadBehaviourStub([]);

        $stub->get('attribute');
    }

    public function testUnknownMethod(): void
    {
        $this->expectException(InvalidMethodCallException::class);
        $this->expectExceptionMessageRegExp('/^Method ".+::unknownMethod" does not exist$/');

        $stub = new PayloadBehaviourStub([]);

        $stub->unknownMethod();
    }

    public function testInvalidMethodArguments(): void
    {
        $this->expectException(InvalidMethodCallException::class);
        $this->expectExceptionMessageRegExp('/^Method ".+::getParameter" should be called with no parameters$/');

        $stub = new PayloadBehaviourStub([]);

        $stub->getParameter('none');
    }
}
