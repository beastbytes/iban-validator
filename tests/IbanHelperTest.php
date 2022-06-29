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
    public function testIbanCreate(): void
    {
        /** @var array $testIbans */
        $testIbans = require __DIR__ . '/testIbans.php';

        foreach ($testIbans as $iban => $data) {
            $this->assertSame($iban, Iban::generateIban($data[0], $data[1]));
        }
    }

    public function testBadData(): void
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

    public function testGetFields()
    {
        /** @var array $testIbans */
        $testIbans = require __DIR__ . '/testIbans.php';
        /** @var array $ibanFormats */
        $ibanFormats = require dirname(__DIR__) . '/src/ibanFormats.php';

        foreach ($testIbans as $iban => $data) {
            $country = substr($iban, 0, 2);
            $fieldValues = $data[1];
            array_unshift($fieldValues, substr($iban, 2, 2)); // add the IBAN check digits
            $fields = Iban::getFields($iban);
            $this->assertSame(array_combine($ibanFormats[$country]['fields'], $fieldValues), $fields);
        }
    }
}
