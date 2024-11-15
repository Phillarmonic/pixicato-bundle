<?php

namespace Phillarmonic\PIXicato;

enum PixType: string
{
    case CPF = 'cpf';
    case CNPJ = 'cnpj';
    case EMAIL = 'email';
    case PHONE = 'phone';
    case RANDOM = 'random';
    case ANY = 'any';
}
