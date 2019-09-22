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

        $this->assertTrue($stub->has('Parameter'));
        $this->assertSame(100, $stub->get('Parameter'));
        $this->assertSame(100, $stub->getParameter());

        $this->assertTrue($stub->has('argument'));
        $this->assertSame('value', $stub->get('argument'));
        $this->assertSame('value', $stub->getArgument());
    }

    public function testPayloadParsing(): void
    {
        $stub = new PayloadBehaviourStub(['value' => 'myValue']);

        $this->assertTrue($stub->has('value'));
        $this->assertSame('myvalue', $stub->get('value'));

        $this->assertSame(['value' => 'myValue'], $stub->getPayload());
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
