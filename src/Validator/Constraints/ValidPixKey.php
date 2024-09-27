<?php

namespace Phillarmonic\PIXicato\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Phillarmonic\PIXicato\PixType;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class ValidPixKey extends Constraint
{
    public string $message = 'The value "{{ value }}" is not a valid PIX key.';
    public string $typeMismatchMessage = 'The PIX key "{{ value }}" is of type "{{ actual_type }}", but "{{ expected_type }}" was expected.';
    public ?string $expectedType = 'any';

    public function __construct(
        string $message = null,
        string $typeMismatchMessage = null,
        string $expectedType = null,
        array $groups = null,
        mixed $payload = null,
        array $options = []
    ) {
        parent::__construct($options, $groups, $payload);

        $this->message = $message ?? $this->message;
        $this->typeMismatchMessage = $typeMismatchMessage ?? $this->typeMismatchMessage;
        $this->expectedType = $expectedType ?? $this->expectedType;

        if (!in_array($this->expectedType, ['any', 'cpf', 'cnpj', 'email', 'phone'])) {
            throw new InvalidOptionsException(
                "The option 'expectedType' must be one of 'any', 'cpf', 'cnpj', 'email', or 'phone'.",
                ['expectedType']
            );
        }
    }
}