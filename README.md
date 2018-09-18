[![PHP version](https://img.shields.io/badge/PHP-%3E%3D7.1-8892BF.svg?style=flat-square)](http://php.net)
[![Latest Version](https://img.shields.io/packagist/v/phpgears/dto.svg?style=flat-square)](https://packagist.org/packages/phpgears/dto)
[![License](https://img.shields.io/github/license/phpgears/dto.svg?style=flat-square)](https://github.com/phpgears/dto/blob/master/LICENSE)

[![Build Status](https://img.shields.io/travis/phpgears/dto.svg?style=flat-square)](https://travis-ci.org/phpgears/dto)
[![Style Check](https://styleci.io/repos/148840961/shield)](https://styleci.io/repos/148840961)
[![Code Quality](https://img.shields.io/scrutinizer/g/phpgears/dto.svg?style=flat-square)](https://scrutinizer-ci.com/g/phpgears/dto)
[![Code Coverage](https://img.shields.io/coveralls/phpgears/dto.svg?style=flat-square)](https://coveralls.io/github/phpgears/dto)

[![Total Downloads](https://img.shields.io/packagist/dt/phpgears/dto.svg?style=flat-square)](https://packagist.org/packages/phpgears/dto/stats)
[![Monthly Downloads](https://img.shields.io/packagist/dm/phpgears/dto.svg?style=flat-square)](https://packagist.org/packages/phpgears/dto/stats)

# DTO

General purpose immutable Data Transfer Objects for PHP

This library provides a means to implement DTO classes as long as three different implementations of general purpose abstract DTO objects you can extend from

This DTO objects are immutable as can be thanks to [gears/immutability](https://github.com/phpgears/immutability) that means once the DTO is created there is no way a value on it is mutated (inside PHP boundaries)

## Installation

### Composer

```
composer require phpgears/dto
```

## Usage

Require composer autoload file

```php
require './vendor/autoload.php';
```

You can use ImmutabilityBehaviour and PayloadBehaviour in any object you want to have DTO functionality

```php
use Gears\Immutability\ImmutabilityBehaviour;
use Gears\DTO\PayloadBehaviour;

class MyDTO implements MyDTOInterface
{
    use ImmutabilityBehaviour;
    use PayloadBehaviour;

    public function __construct(array $parameters)
    {
        $this->checkImmutability();

        $this->setPayload($parameters);
    }

    final public function getAllowedInterfaces(): array
    {
        return [MyDTOInterface::class];
    }
}
```

Even better this boilerplate is already in place for you by extending `Gears\DTO\AbstractDTO` or `Gears\DTO\AbstractScalarDTO` classes

DTO constructors are declared protected forcing you to create "named constructors", this have a very useful feature, you get to typehint your DTO parameters

```php
use Gears\DTO\AbstractDTO;

/**
 * @method getName(): string
 * @method getLastname(): string
 * @method getDate(): \DateTimeImmutable
 */
class MyDTO extends AbstractDTO
{
    public static function createFromXXX(
        string $name, 
        string $lastName, 
        \DateTimeImmutable $date
    ): self {
        return new $this([
            'name' => $name,
            'lastName' => $lastName,
            'date' => $date->setTimezone(new \DateTimeZone('UTC')),
        ]);
    }
}
```

`Gears\DTO\AbstractScalarDTO` ensures all payload is either a scalar value (null, string, int, float or bool) or an array of scalar values. It is the perfect match to create Domain Events, or CQRS commands/queries

Finally `Gears\DTO\AbstractDTOCollection` is a special type of DTO that only accepts a list of elements, being this elements implementations of DTO interface itself. This object is meant to be used as a return value when several DTOs should be returned, for example from a DDBB request

You can take advantage of magic method __call on DTO objects to access properties. If you plan to use this feature it's best to annotate this magic accessors at class level with `@method` phpDoc tag, this will help you're IDE autocompletion

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/phpgears/dto/issues). Have a look at existing issues before.

See file [CONTRIBUTING.md](https://github.com/phpgears/dto/blob/master/CONTRIBUTING.md)

## License

See file [LICENSE](https://github.com/phpgears/dto/blob/master/LICENSE) included with the source code for a copy of the license terms.
