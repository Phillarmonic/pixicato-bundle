<?php

// Assuming this file is in the root of your project
require_once __DIR__ . '/vendor/autoload.php';

use Phillarmonic\PIXicato\PixKey;
use Phillarmonic\PIXicato\PixType;

function runTest($value, $expectedType, $shouldBeValid) {
    $pixKey = new PixKey($value, $expectedType);
    $result = $pixKey->isValid() ? "valid" : "invalid";
    $expected = $shouldBeValid ? "valid" : "invalid";
    $pass = $result === $expected ? "PASS" : "FAIL";

    echo sprintf(
        "Testing %s with expected type %s: %s (Expected: %s) - %s\n",
        $value,
        $expectedType->value,
        $result,
        $expected,
        $pass
    );
}

echo "Running PixKey tests...\n\n";

// Test valid keys
runTest('123.456.789-09', PixType::CPF, true);
runTest('12.345.678/0001-95', PixType::CNPJ, true);
runTest('test@example.com', PixType::EMAIL, true);
runTest('+5511987654321', PixType::PHONE, true);

// Test invalid keys
runTest('111.111.111-11', PixType::CPF, false);
runTest('11.111.111/1111-11', PixType::CNPJ, false);
runTest('invalid.email@', PixType::EMAIL, false);
runTest('123456', PixType::PHONE, false);

// Test type mismatches
runTest('123.456.789-09', PixType::CNPJ, false);
runTest('12.345.678/0001-95', PixType::CPF, false);
runTest('test@example.com', PixType::PHONE, false);
runTest('+5511987654321', PixType::EMAIL, false);

// Test with ANY type
runTest('123.456.789-09', PixType::ANY, true);
runTest('12.345.678/0001-95', PixType::ANY, true);
runTest('test@example.com', PixType::ANY, true);
runTest('+5511987654321', PixType::ANY, true);

echo "\nTests completed.\n";