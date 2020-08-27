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

use Gears\DTO\Exception\DTOViolationException;
use Gears\DTO\Exception\InvalidMethodCallException;
use Gears\DTO\Exception\InvalidParameterException;
use Gears\DTO\Tests\Stub\PayloadBehaviourExtendedStub;
use Gears\DTO\Tests\Stub\PayloadBehaviourInvalidCallStub;
use Gears\DTO\Tests\Stub\PayloadBehaviourInvalidImmutableCallStub;
use Gears\DTO\Tests\Stub\PayloadBehaviourStub;
use Gears\Immutability\Exception\ImmutabilityViolationException;
use PHPUnit\Framework\TestCase;

/**
 * PayloadBehaviour trait test.
 */
class PayloadBehaviourTest extends TestCase
{
    public function testNoImmutabilityAssertion(): void
    {
        $this->expectException(ImmutabilityViolationException::class);
        $this->expectExceptionMessageRegExp(
            '/^Immutability check available only through "setPayload" method, called from ".+::__construct"$/'
        );

        new PayloadBehaviourInvalidImmutableCallStub([]);
    }

    public function testSingleCall(): void
    {
        $this->expectException(DTOViolationException::class);
        $this->expectExceptionMessageRegExp(
            '/^Payload already set for DTO ".+"$/'
        );

        PayloadBehaviourStub::callPayload();
    }

    public function testInvalidCall(): void
    {
        $this->expectException(DTOViolationException::class);
        $this->expectExceptionMessageRegExp(
            '/^DTO payload set available only through ".+" methods, called from ".+::instantiate"$/'
        );

        PayloadBehaviourInvalidCallStub::instantiate();
    }

    public function testPayload(): void
    {
        $stub = new PayloadBehaviourStub(['parameter' => 100, 'value' => 'myValue']);

        static::assertSame(100, $stub->get('parameter'));
        static::assertSame(100, $stub->getParameter());
        static::assertSame('myvalue', $stub->get('value'));
        static::assertSame('myvalue', $stub->getValue());
        static::assertEquals(['parameter' => 100, 'value' => 'myvalue'], $stub->getPayload());

        $stubExtended = new PayloadBehaviourExtendedStub(['parameter' => 100, 'extended' => true]);

        static::assertSame(100, $stubExtended->get('parameter'));
        static::assertSame(100, $stubExtended->getParameter());
        static::assertTrue($stubExtended->get('extended'));
        static::assertTrue($stubExtended->getExtended());
        static::assertEquals(['parameter' => 100, 'value' => null, 'extended' => true], $stubExtended->getPayload());
    }

    public function testInvalidPayload(): void
    {
        $this->expectException(InvalidParameterException::class);
        $this->expectExceptionMessageRegExp('/^Payload parameter "attribute" on ".+" does not exist$/');

        new PayloadBehaviourStub(['attribute' => 'unknown']);
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
