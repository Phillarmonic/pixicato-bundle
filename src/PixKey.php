<?php

namespace Phillarmonic\PIXicato;

use Phillarmonic\CpfCnpj\CPF;
use Phillarmonic\CpfCnpj\CNPJ;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use Symfony\Component\Validator\Constraints as Assert;

class PixKey
{
    private bool $isValid = false;
    private ?PixType $type = null;

    public function __construct(
        private readonly string $value,
        public readonly PixType $expectedPixType = PixType::ANY
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->isCPF()) {
            $this->type = PixType::CPF;
        } elseif ($this->isCNPJ()) {
            $this->type = PixType::CNPJ;
        } elseif ($this->isEmail()) {
            $this->type = PixType::EMAIL;
        } elseif ($this->isPhone()) {
            $this->type = PixType::PHONE;
        }

        $this->isValid = $this->type !== null &&
            ($this->expectedPixType === PixType::ANY || $this->expectedPixType === $this->type);
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function isCPF(): bool
    {
        $cpf = new CPF($this->value);
        return $cpf->isValid();
    }

    public function isCNPJ(): bool
    {
        $cnpj = new CNPJ($this->value);
        return $cnpj->isValid();
    }

    public function isEmail(): bool
    {
        return (bool) filter_var($this->value, FILTER_VALIDATE_EMAIL);
    }

    public function isPhone(): bool
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $phoneNumber = $phoneUtil->parse($this->value, 'BR');
            return $phoneUtil->isValidNumber($phoneNumber);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getType(): ?PixType
    {
        return $this->type;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}