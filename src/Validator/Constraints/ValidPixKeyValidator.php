<?php

namespace Phillarmonic\PIXicato\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Phillarmonic\PIXicato\PixKey;
use Phillarmonic\PIXicato\PixType;

class ValidPixKeyValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidPixKey) {
            throw new UnexpectedTypeException($constraint, ValidPixKey::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $expectedType = $constraint->expectedType === 'any' ? PixType::ANY : PixType::from($constraint->expectedType);
        $pixKey = new PixKey($value, $expectedType);

        if (!$pixKey->isValid()) {
            if ($pixKey->getType() !== null && $pixKey->getType() !== $expectedType) {
                // The key is valid, but not of the expected type
                $this->addViolation($constraint, $value, $pixKey->getType()->value, true);
            } else {
                // The key is not valid for any type
                $this->addViolation($constraint, $value, null);
            }
        }
    }

    private function addViolation(ValidPixKey $constraint, string $value, ?string $actualType, bool $typeMismatch = false): void
    {
        if ($typeMismatch) {
            $this->context->buildViolation($constraint->typeMismatchMessage)
                          ->setParameter('{{ value }}', $value)
                          ->setParameter('{{ expected_type }}', $constraint->expectedType)
                          ->setParameter('{{ actual_type }}', $actualType ?? 'invalid')
                          ->addViolation();
        } else {
            $this->context->buildViolation($constraint->message)
                          ->setParameter('{{ value }}', $value)
                          ->addViolation();
        }
    }
}
