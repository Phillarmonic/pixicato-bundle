<?php

namespace Phillarmonic\PIXicato\Tests\Validator\Constraints;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Phillarmonic\PIXicato\Validator\Constraints\ValidPixKey;
use Phillarmonic\PIXicato\Validator\Constraints\ValidPixKeyValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class ValidPixKeyValidatorTest extends TestCase
{
    private $context;
    private $validator;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->validator = new ValidPixKeyValidator();
        $this->validator->initialize($this->context);
    }

    #[DataProvider('validPixKeysProvider')]
    public function testValidPixKeys(
        $value,
        $expectedType
    ): void {
        $constraint               = new ValidPixKey();
        $constraint->expectedType = $expectedType;

        $this->context->expects($this->never())
                      ->method('buildViolation')
        ;

        $this->validator->validate($value, $constraint);
    }

    #[DataProvider('invalidPixKeysProvider')]
    public function testInvalidPixKeys($value): void
    {
        $constraint = new ValidPixKey();

        $violationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $violationBuilder->expects($this->once())
                         ->method('setParameter')
                         ->willReturnSelf()
        ;
        $violationBuilder->expects($this->once())
                         ->method('addViolation')
        ;

        $this->context->expects($this->once())
                      ->method('buildViolation')
                      ->with($constraint->message)
                      ->willReturn($violationBuilder)
        ;

        $this->validator->validate($value, $constraint);
    }

    #[DataProvider('typeMismatchProvider')]
    public function testTypeMismatch(
        $value,
        $expectedType
    ): void {
        $constraint               = new ValidPixKey();
        $constraint->expectedType = $expectedType;

        $violationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $violationBuilder->expects($this->exactly(3))
                         ->method('setParameter')
                         ->willReturnSelf()
        ;
        $violationBuilder->expects($this->once())
                         ->method('addViolation')
        ;

        $this->context->expects($this->once())
                      ->method('buildViolation')
                      ->with($constraint->typeMismatchMessage)
                      ->willReturn($violationBuilder)
        ;

        $this->validator->validate($value, $constraint);
    }

    public static function validPixKeysProvider(): array
    {
        return [
            ['123.456.789-09', 'cpf'],
            ['12.345.678/0001-95', 'cnpj'],
            ['test@example.com', 'email'],
            ['+5511987654321', 'phone'],
            ['123e4567-e89b-12d3-a456-426655440000', 'random'],
        ];
    }

    public static function invalidPixKeysProvider(): array
    {
        return [
            ['111.111.111-11'],
            ['11.111.111/1111-11'],
            ['invalid.email@'],
            ['123456'],
            ['randomstring'],
            ['123e4567-e89b-12d3-a456-42665544000g'],
        ];
    }

    public static function typeMismatchProvider(): array
    {
        return [
            ['123.456.789-09', 'email'],
            ['test@example.com', 'cpf'],
            ['+5511987654321', 'cnpj'],
            ['123e4567-e89b-12d3-a456-426655440000', 'email'],
            ['test@example.com', 'random'],
        ];
    }
}
