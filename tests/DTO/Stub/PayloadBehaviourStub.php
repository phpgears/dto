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
class PayloadBehaviourStub implements DTO
{
    use PayloadBehaviour;

    protected $parameter;

    protected $value;

    /**
     * PayloadTraitStub constructor.
     *
     * @param array<string, mixed> $payload
     */
    public function __construct(array $payload)
    {
        $this->setPayload($payload);
    }

    protected function testPayload(): void
    {
        $this->setPayload([]);
    }

    public static function callPayload(): void
    {
        $dto = new static([]);
        $dto->testPayload();
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value ? \strtolower($this->value) : null;
    }

    protected function getAllowedInterfaces(): array
    {
        return [DTO::class];
    }
}
