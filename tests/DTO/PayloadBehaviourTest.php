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

    /**
     * @expectedException \Gears\DTO\Exception\InvalidParameterException
     * @expectedExceptionMessageRegExp /Payload parameter attribute on.+ does not exist/
     */
    public function testNonExistentPayload(): void
    {
        $stub = new PayloadBehaviourStub([]);

        $stub->get('attribute');
    }

    /**
     * @expectedException \Gears\DTO\Exception\InvalidMethodCallException
     * @expectedExceptionMessageRegExp /^Method .+::unknownMethod does not exist$/
     */
    public function testUnknownMethod(): void
    {
        $stub = new PayloadBehaviourStub([]);

        $stub->unknownMethod();
    }

    /**
     * @expectedException \Gears\DTO\Exception\InvalidMethodCallException
     * @expectedExceptionMessageRegExp /^.+::getParameter method should be called with no parameters$/
     */
    public function testInvalidMethodArguments(): void
    {
        $stub = new PayloadBehaviourStub([]);

        $stub->getParameter('none');
    }
}
