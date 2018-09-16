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
        $stub = new PayloadBehaviourStub(['parameter' => 100]);

        $this->assertTrue($stub->has('parameter'));
        $this->assertSame(100, $stub->get('parameter'));
    }

    public function testPayloadParsing(): void
    {
        $stub = new PayloadBehaviourStub(['value' => 'myValue']);

        $this->assertTrue($stub->has('value'));
        $this->assertSame('myvalue', $stub->get('value'));
        $this->assertSame(['value' => 'myvalue'], $stub->getPayload());
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
}
