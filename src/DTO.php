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

/**
 * Data Transfer Object interface.
 */
interface DTO
{
    /**
     * Get parameter.
     *
     * @param string $parameter
     *
     * @return mixed
     */
    public function get(string $parameter);

    /**
     * Export payload.
     *
     * @return array<string, mixed>
     */
    public function getPayload(): array;
}
