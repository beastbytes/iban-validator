<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

use BeastBytes\Iban\Validator\Rule\Iban;

return [
    Iban::INCORRECT_INPUT_MESSAGE => 'Nieprawidłowy typ: „{type}”. IBAN musi być ciągiem znaków.',
    Iban::INVALID_CHECKSUM_MESSAGE => 'Suma kontrolna jest nieprawidłowa.',
    Iban::INVALID_COUNTRY_MESSAGE => 'Kod kraju „{country}” jest nieprawidłowy.',
    Iban::INVALID_STRUCTURE_MESSAGE => 'Struktura IBAN jest nieprawidłowa dla kraju "{country}".'
];
