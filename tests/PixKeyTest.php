<?php

namespace Phillarmonic\PIXicato\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Phillarmonic\PIXicato\PixKey;
use Phillarmonic\PIXicato\PixType;

class PixKeyTest extends TestCase
{
    #[DataProvider('validPixKeysProvider')]
    public function testValidPixKeys(string $value, PixType $expectedType, PixType $actualType): void
    {
        $pixKey = new PixKey($value, $expectedType);
        $this->assertTrue($pixKey->isValid());
        $this->assertEquals($actualType, $pixKey->getType());
        $this->assertEquals($expectedType, $pixKey->expectedPixType);
    }

    #[DataProvider('invalidPixKeysProvider')]
    public function testInvalidPixKeys(string $value, PixType $expectedType): void
    {
        $pixKey = new PixKey($value, $expectedType);
        $this->assertFalse($pixKey->isValid());
        $this->assertNull($pixKey->getType());
        $this->assertEquals($expectedType, $pixKey->expectedPixType);
    }

    #[DataProvider('pixKeyTypeChecksProvider')]
    public function testPixKeyTypeChecks(string $value, string $typeMethod, bool $expected): void
    {
        $pixKey = new PixKey($value);
        $this->assertEquals($expected, $pixKey->$typeMethod());
    }

    #[DataProvider('mismatchedTypesProvider')]
    public function testMismatchedTypes(string $value, PixType $expectedType): void
    {
        $pixKey = new PixKey($value, $expectedType);
        $this->assertFalse($pixKey->isValid());
        $this->assertNotEquals($expectedType, $pixKey->getType());
        $this->assertEquals($expectedType, $pixKey->expectedPixType);
    }

    public static function validPixKeysProvider(): array
    {
        return [
            'Valid CPF with ANY' => ['123.456.789-09', PixType::ANY, PixType::CPF],
            'Valid CNPJ with ANY' => ['12.345.678/0001-95', PixType::ANY, PixType::CNPJ],
            'Valid Email with ANY' => ['test@example.com', PixType::ANY, PixType::EMAIL],
            'Valid Phone with ANY' => ['+5511987654321', PixType::ANY, PixType::PHONE],
            'Valid CPF with CPF' => ['123.456.789-09', PixType::CPF, PixType::CPF],
            'Valid CNPJ with CNPJ' => ['12.345.678/0001-95', PixType::CNPJ, PixType::CNPJ],
            'Valid Email with Email' => ['test@example.com', PixType::EMAIL, PixType::EMAIL],
            'Valid Phone with Phone' => ['+5511987654321', PixType::PHONE, PixType::PHONE],
            'Valid Random with ANY' => ['123e4567-e89b-12d3-a456-426655440000', PixType::ANY, PixType::RANDOM],
            'Valid Random with Random' => ['123e4567-e89b-12d3-a456-426655440000', PixType::RANDOM, PixType::RANDOM],
        ];
    }

    public static function invalidPixKeysProvider(): array
    {
        return [
            'Invalid CPF with ANY' => ['111.111.111-11', PixType::ANY],
            'Invalid CNPJ with ANY' => ['11.111.111/1111-11', PixType::ANY],
            'Invalid Email with ANY' => ['invalid.email@', PixType::ANY],
            'Invalid Phone with ANY' => ['123456', PixType::ANY],
            'Invalid Random with ANY' => ['123e4567-12d3-a456-42665544000g', PixType::ANY], // Fixed
            'Invalid CPF with CPF' => ['111.111.111-11', PixType::CPF],
            'Invalid CNPJ with CNPJ' => ['11.111.111/1111-11', PixType::CNPJ],
            'Invalid Email with Email' => ['invalid.email@', PixType::EMAIL],
            'Invalid Phone with Phone' => ['123456', PixType::PHONE],
            'Invalid Random with Random' => ['123e4567-12d3-a456-42665544000g', PixType::RANDOM], // Fixed
        ];
    }

    public static function pixKeyTypeChecksProvider(): array
    {
        return [
            'Is CPF' => ['123.456.789-09', 'isCPF', true],
            'Is not CPF' => ['12.345.678/0001-95', 'isCPF', false],
            'Is CNPJ' => ['12.345.678/0001-95', 'isCNPJ', true],
            'Is not CNPJ' => ['123.456.789-09', 'isCNPJ', false],
            'Is Email' => ['test@example.com', 'isEmail', true],
            'Is not Email' => ['123.456.789-09', 'isEmail', false],
            'Is Phone' => ['+5511987654321', 'isPhone', true],
            'Is not Phone' => ['123.456.789-09', 'isPhone', false],
            'Is Random' => ['123e4567-e89b-12d3-a456-426655440000', 'isRandom', true],
            'Is not Random' => ['123.456.789-09', 'isRandom', false],
        ];
    }

    public static function mismatchedTypesProvider(): array
    {
        return [
            'CPF with CNPJ type' => ['123.456.789-09', PixType::CNPJ],
            'CNPJ with CPF type' => ['12.345.678/0001-95', PixType::CPF],
            'Email with Phone type' => ['test@example.com', PixType::PHONE],
            'Phone with Email type' => ['+5511987654321', PixType::EMAIL],
            'Random with Email type' => ['123e4567-e89b-12d3-a456-426655440000', PixType::EMAIL],
            'Email with Random type' => ['test@example.com', PixType::RANDOM],

        ];
    }

    public function testGetValue(): void
    {
        $value = '123.456.789-09';
        $pixKey = new PixKey($value);
        $this->assertEquals($value, $pixKey->getValue());
    }

    public function testExpectedPixTypeAccessibility(): void
    {
        $pixKey = new PixKey('123.456.789-09', PixType::CPF);
        $this->assertEquals(PixType::CPF, $pixKey->expectedPixType);
    }

    #[DataProvider('anyTypeDetectionProvider')]
    public function testAnyTypeDetection(string $value, PixType $expectedDetectedType): void
    {
        $pixKey = new PixKey($value, PixType::ANY);
        $this->assertTrue($pixKey->isValid());
        $this->assertEquals($expectedDetectedType, $pixKey->getType());
        $this->assertEquals(PixType::ANY, $pixKey->expectedPixType);
    }

    public static function anyTypeDetectionProvider(): array
    {
        return [
            'CPF with ANY type' => ['123.456.789-09', PixType::CPF],
            'CNPJ with ANY type' => ['12.345.678/0001-95', PixType::CNPJ],
            'Email with ANY type' => ['test@example.com', PixType::EMAIL],
            'Phone with ANY type' => ['+5511987654321', PixType::PHONE],
            'Random with ANY type' => ['123e4567-e89b-12d3-a456-426655440000', PixType::RANDOM],
        ];
    }

    public function testNullValue(): void
    {
        $pixKey = new PixKey(null, PixType::ANY);
        $this->assertFalse($pixKey->isValid());
//        $this->assertNull($pixKey->getType());
    }
}
