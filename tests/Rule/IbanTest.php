<?php
/**
 * @copyright Copyright © 2022 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\Iban\Tests\Rule;

use BeastBytes\Iban\Rule\Iban;
use Yiisoft\Validator\ParametrizedRuleInterface;

class IbanTest extends AbstractRuleTest
{
    public function optionsDataProvider(): array
    {
        return [
            [
                new Iban(),
                [
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

    protected function getRule(): ParametrizedRuleInterface
    {
        return new Iban();
    }
}
