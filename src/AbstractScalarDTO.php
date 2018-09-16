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

namespace Gears\DTO;

use Gears\Immutability\ImmutabilityBehaviour;

/**
 * Abstract immutable and only scalar values Data Transfer Object.
 */
abstract class AbstractScalarDTO implements DTO
{
    use ImmutabilityBehaviour;
    use ScalarPayloadBehaviour;

    /**
     * AbstractSerializableDTO constructor.
     *
     * @param array<string, mixed> $parameters
     */
    final protected function __construct(array $parameters)
    {
        $this->checkImmutability();

        $this->setPayload($parameters);
    }

    /**
     * {@inheritdoc}
     *
     * @return string[]
     */
    final protected function getAllowedInterfaces(): array
    {
        return [DTO::class];
    }
}
