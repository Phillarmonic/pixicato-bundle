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
        $this->assertEquals('random', PixType::RANDOM->value);
        $this->assertEquals('any', PixType::ANY->value);
    }

    public function testPixTypeCount(): void
    {
        $this->assertCount(6, PixType::cases());
    }
}
