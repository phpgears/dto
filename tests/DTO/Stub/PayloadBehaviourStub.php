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

use Gears\DTO\PayloadBehaviour;

/**
 * PayloadBehaviour trait stub class.
 *
 * @method bool hasParameter
 * @method int  getParameter
 */
class PayloadBehaviourStub
{
    use PayloadBehaviour;

    /**
     * PayloadTraitStub constructor.
     *
     * @param array<string, mixed> $parameters
     */
    public function __construct(array $parameters)
    {
        $this->setPayload($parameters);
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function outputValue(string $value): string
    {
        return \strtolower($value);
    }
}
