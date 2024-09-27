# PIXicato Bundle

A Symfony bundle for validating Brazilian PIX keys.

Pluck the right key every time :)

## Installation

```bash
composer require phillarmonic/pixicato-bundle
```

## Usage

### Validating a PIX key

```php
use Phillarmonic\PIXicato\PixKey;

$pixKey = new PixKey('example@email.com');
if ($pixKey->isValid()) {
    echo "Valid PIX key of type: " . $pixKey->getType()->value;
} else {
    echo "Invalid PIX key";
}

// You can do it in a one-liner too
echo (new PixKey('example@example.com'))->isValid() ? 'Valid' : 'Invalid';
```

### Using the Symfony validator

```php
use Phillarmonic\PIXicato\Validator\Constraints as PIXAssert;

class User
{
    #[PIXAssert\ValidPixKey(
        message: 'This is not a valid PIX key.',
        expectedType: 'email'
    )]
    private string $pixKey;

    // ...
}
```

## Supported PIX key types

- CPF
- CNPJ
- Email
- Phone number

## License

This library is released under the MIT License. See the bundled LICENSE file for details.