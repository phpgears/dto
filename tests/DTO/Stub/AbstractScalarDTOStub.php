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

use Gears\DTO\AbstractScalarDTO;

/**
 * AbstractScalarDTO stub class.
 *
 * @method getParameter(): int
 * @method getObject(): self
 * @method getCollection(): \Gears\DTO\AbstractDTOCollection
 */
class AbstractScalarDTOStub extends AbstractScalarDTO
{
    /**
     * Get from array.
     *
     * @param array<string, mixed> $parameters
     *
     * @return self
     */
    public static function fromArray(array $parameters): self
    {
        return new self($parameters);
    }
}
