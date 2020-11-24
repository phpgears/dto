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

namespace Gears\DTO\Tests\Stub;

use Gears\DTO\DTO;
use Gears\DTO\PayloadBehaviour;

/**
 * PayloadBehaviour trait stub class.
 *
 * @method getParameter()
 */
class PayloadBehaviourInvalidImmutableCallStub implements DTO
{
    use PayloadBehaviour;

    /**
     * PayloadTraitStub constructor.
     *
     * @param iterable<mixed> $payload
     */
    public function __construct(iterable $payload)
    {
        $this->assertImmutable();
        $this->setPayload($payload);
    }

    protected function getAllowedInterfaces(): array
    {
        return [DTO::class];
    }
}