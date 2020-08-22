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
class PayloadBehaviourInvalidCallStub implements DTO
{
    use PayloadBehaviour;

    /**
     * PayloadTraitStub constructor.
     *
     * @param array<string, mixed> $parameters
     */
    public function __construct(array $parameters)
    {
        $this->assertImmutable();
        $this->setPayload($parameters);
    }

    protected function getAllowedInterfaces(): array
    {
        return [DTO::class];
    }
}
