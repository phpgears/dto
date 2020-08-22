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

You can use PayloadBehaviour in any object you want to have immutable DTO functionality

```php
use Gears\DTO\DTO;
use Gears\DTO\PayloadBehaviour;

class MyDTO implements DTO, MyDTOInterface
{
    use PayloadBehaviour;

    private $parameter;

    public function __construct(array $parameters)
    {
        $this->setPayload($parameters);
    }

    final public function getAllowedInterfaces(): array
    {
        return [DTO::class, MyDTOInterface::class];
    }
}
```

If you just need a plain DTO object it gets a lot easier, this boilerplate code is already in place for you by extending `Gears\DTO\AbstractDTO` or `Gears\DTO\AbstractScalarDTO` classes

Protected constructor forces you to create "named constructors", this has a very useful side effect, you get to type-hint all your DTO parameters

```php
use Gears\DTO\AbstractScalarDTO;

/**
 * @method getName(): string
 * @method getAge(): int
 */
class MyDTO extends AbstractScalarDTO
{
    /** 
     * @var string 
     */
    private $name;

    /**
     * @var int
     */
    private $age;

    /**
     * @var string 
     */
    private $date;

    /**
     * Custom named constructor.
     *
     * @param string            $name
     * @param int               $age
     * @param DateTimeImmutable $date
     * 
     * @return self
     */
    public static function instantiate(
        string $name,
        int $age,
        \DateTimeImmutable $date
    ): self {
        return new static([
            'name' => $name,
            'age' => $age,
            'date' => $date->format(\DateTime::ATOM), // Transform to scalar
        ]);
    }

    /**
     * Transform back to original.
     * 
     * @retrun \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(\DateTime::ATOM, $this->date);
    }
}

$myDto = MyDTO::instantiate('name', 24, new \DateTimeImmutable('now'));

$name = $myDto->get('name');
$name = $myDto->getName();
$age = $myDto->get('age');
$age = $myDto->getAge();
$date = $myDto->get('date');
$date = $myDto->getDate();
```

The only public methods accepted are DTO parameter accessors in the form "getParameter()"

Access to DTO parameters can be done by the "get" method or with a little help of magic __call method. If you plan to use __the call feature it's best to annotate this magic accessors at class level with `@method` phpDoc tag, this will help your IDE auto-completion

The difference between `Gears\DTO\AbstractDTO` and `Gears\DTO\AbstractScalarDTO` is that the later ensures all payload is either a scalar value (null, string, int, float or bool) or an array of scalar values. Its purpose is to ensure the object can be securely serialized, it is the perfect match to create Domain Events, or Commands/Queries for CQRS

Finally `Gears\DTO\AbstractDTOCollection` is a special type of DTO that only accepts a list of elements, being these elements implementations of DTO interface itself. This object is meant to be used as a return value when several DTOs should be returned, for example from a DDBB query result

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/phpgears/dto/issues). Have a look at existing issues before.

See file [CONTRIBUTING.md](https://github.com/phpgears/dto/blob/master/CONTRIBUTING.md)

## License

See file [LICENSE](https://github.com/phpgears/dto/blob/master/LICENSE) included with the source code for a copy of the license terms.
