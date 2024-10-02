<?php

namespace Phillarmonic\PIXicato\Tests;

use PHPUnit\Framework\TestCase;
use Phillarmonic\PIXicato\PixType;

class PixTypeTest extends TestCase
{
    public function testPixTypeValues(): void
    {
        $this->assertEquals('cpf', PixType::CPF->value);
        $this->assertEquals('cnpj', PixType::CNPJ->value);
        $this->assertEquals('email', PixType::EMAIL->value);
        $this->assertEquals('phone', PixType::PHONE->value);
    }

    public function testPixTypeCount(): void
    {
        $this->assertCount(5, PixType::cases());
    }
}
