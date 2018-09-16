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

use Gears\DTO\AbstractDTOCollection;
use Gears\DTO\Exception\InvalidParameterException;

/**
 * AbstractDTOCollection with invalid type stub class.
 */
class AbstractDTOCollectionInvalidTypeStub extends AbstractDTOCollection
{
    /**
     * {@inheritdoc}
     */
    protected function getAllowedType(): string
    {
        return InvalidParameterException::class;
    }
}
