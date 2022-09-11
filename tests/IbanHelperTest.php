<?php
/**
 * @copyright Copyright © 2022 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\Iban\Tests;

use BeastBytes\Iban\Iban;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class IbanHelperTest extends TestCase
{
    public function test_generate_iban(): void
    {
        /** @var array $testIbans */
        $testIbans = require __DIR__ . '/testIbans.php';

        foreach ($testIbans as $iban => $data) {
            $this->assertSame($iban, Iban::generateIban($data[0], $data[1]));
        }
    }

    public function test_bad_data(): void
    {
        $badIbans = [
            [
                'country' => 'XX',
                'data' => 'BARC20201630093459',
                'message' => 'Country "XX" does not use IBAN'
            ],
            [
                'country' => 'GB',
                'data' => 'BARC20201630093459',
                'message' => 'Data not the correct format for "GB"'
            ],
            [
                'country' => 'GB',
                'data' => 'BARC20201530093A59',
                'message' => 'Data not the correct format for "GB"'
            ],
            [
                'country' => 'GB',
                'data' => 'BARCO0201530093459',
                'message' => 'Data not the correct format for "GB"'
            ],
        ];

        foreach ($badIbans as $iban) {
            $this->expectException(InvalidArgumentException::class);
            $this->expectExceptionMessage($iban['message']);
            Iban::generateIban($iban['country'], $iban['data']);
        }
    }

    public function test_get_fields()
    {
        /** @var array $testIbans */
        $testIbans = require __DIR__ . '/testIbans.php';
        /** @var array $ibanFormats */
        $ibanFormats = require dirname(__DIR__) . '/src/ibanFormats.php';

        foreach ($testIbans as $testIban => $data) {
            $country = substr($testIban, 0, 2);
            $fieldValues = $data[1];
            array_unshift($fieldValues, substr($testIban, 2, 2)); // add the IBAN check digits
            $fields = Iban::getFields($testIban);
            $this->assertSame(array_combine($ibanFormats[$country]['fields'], $fieldValues), $fields);
        }
    }

    public function test_uses_iban()
    {
        /** @var array $testIbans */
        $testIbans = require __DIR__ . '/testIbans.php';

        foreach ($testIbans as $data) {
            $this->assertTrue(Iban::usesIban($data[0]));
        }

        foreach (['XX', 'GBP', 'A', 'gb'] as $badCountry) {
            // Bad countries are, respectively:
            // an invalid two-letter code
            // too long - country codes are ISO 3166 alpha-2 codes
            // too short
            // lowercase - country codes, indeed the whole IBAN, must use uppercase letters
            $this->assertFalse(Iban::usesIban($badCountry));
        }
    }
}
