<?php
/**
 * @copyright Copyright © 2022 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace Tests\Rule;

use BeastBytes\Iban\Formats\IbanFormat;
use BeastBytes\Iban\Rule\Iban;
use Yiisoft\Validator\SerializableRuleInterface;

class IbanTest extends AbstractRuleTest
{
    public function optionsDataProvider(): array
    {
        $ibanFormats = new IbanFormat();
        return [
            [
                new Iban($ibanFormats),
                [
                    'ibanFormats' => $ibanFormats,
                    'invalidChecksumMessage' => 'Checksum not valid',
                    'invalidCountryMessage' => [
                        'message' => 'Country code "{country}" not valid',
                        'parameters' => [],
                    ],
                    'invalidStructureMessage' => [
                        'message' => 'IBAN structure not valid for country "{country}"',
                        'parameters' => [],
                    ],
                    'skipOnEmpty' => false,
                    'skipOnError' => false,
                ],
            ],
        ];
    }

    protected function getRule(): SerializableRuleInterface
    {
        return new Iban(new IbanFormat());
    }
}
