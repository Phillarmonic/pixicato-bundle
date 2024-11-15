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
    private ?PixType $detectedType = null;

    public function __construct(
        private readonly ?string $value = null,
        public readonly PixType $expectedPixType = PixType::ANY
    ) {
        $this->validate();
        return $this;
    }

    private function validate(): void {
        if ($this->value === null) {
            $this->isValid = false;
            return;
        }
        if ($this->isCPF()) {
            $this->detectedType = PixType::CPF;
        } elseif ($this->isCNPJ()) {
            $this->detectedType = PixType::CNPJ;
        } elseif ($this->isEmail()) {
            $this->detectedType = PixType::EMAIL;
        } elseif ($this->isPhone()) {
            $this->detectedType = PixType::PHONE;
        } elseif ($this->isRandom()) {
            $this->detectedType = PixType::RANDOM;
        }

        $this->isValid = $this->detectedType !== null &&
            ($this->expectedPixType === PixType::ANY || $this->expectedPixType === $this->detectedType);
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

    public function isRandom(): bool
    {
        return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $this->value);
    }


    public function getType(): ?PixType
    {
        return $this->detectedType;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
