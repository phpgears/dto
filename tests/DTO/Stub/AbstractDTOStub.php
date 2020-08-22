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

use Gears\DTO\AbstractDTO;

/**
 * AbstractDTO trait stub class.
 *
 * @method getParameter()
 */
class AbstractDTOStub extends AbstractDTO
{
    /**
     * @var int|null
     */
    protected $parameter;

    /**
     * @param mixed[] $parameters
     *
     * @return static
     */
    public static function fromArray(array $parameters)
    {
        return new static($parameters);
    }
}
