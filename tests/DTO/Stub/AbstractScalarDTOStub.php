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
 * AbstractScalarDTO trait stub class.
 */
class AbstractScalarDTOStub extends AbstractScalarDTO
{
    protected $parameter;

    /**
     * @param iterable $payload
     *
     * @return static
     */
    public static function fromArray(iterable $payload)
    {
        return new static($payload);
    }

    /**
     * @return int
     */
    public function getParameter(): int
    {
        return $this->parameter * 10;
    }
}
