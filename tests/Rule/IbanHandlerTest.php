<?php

declare(strict_types=1);

namespace BeastBytes\Iban\Tests\Rule;

use BeastBytes\Iban\Rule\Iban;
use BeastBytes\Iban\Rule\IbanHandler;
use Yiisoft\Validator\Error;
use Yiisoft\Validator\Rule\RuleHandlerInterface;

final class IbanHandlerTest extends AbstractRuleValidatorTest
{
    public function failedValidationProvider(): array
    {
        $rule = new Iban(skipOnError: true);

        return [
            [ // Invalid country
                $rule,
                'XX29NWBK60161331926819',
                [new Error('Country code "XX" not valid', [])]
            ],
            [ // Invalid account structure
                $rule,
                'GB12BARC20201530093A59',
                [new Error('IBAN structure not valid for country "GB"', [])]
            ],
            [ // Invalid bank code structure
                $rule,
                'GB78BARCO0201530093459',
                [new Error('IBAN structure not valid for country "GB"', [])]
            ],
            [ // Invalid checksum
                $rule,
                'GB99NWBK60161331926819',
                [new Error($rule->getInvalidChecksumMessage(), [])]
            ],
        ];
    }

    public function passedValidationProvider(): array
    {
        $rule = new Iban();

        $passedValidationProvider = [];
        $ibans = array_keys(require dirname(__DIR__) . '/testIbans.php');
        foreach ($ibans as $iban) {
            $passedValidationProvider[] = [$rule, $iban];
        }

        return $passedValidationProvider;
    }

    public function customErrorMessagesProvider(): array
    {
        $rule = new Iban(
            invalidChecksumMessage: 'Custom Invalid Checksum message',
            invalidCountryMessage: 'Custom Invalid Country message',
            invalidStructureMessage: 'Custom Invalid Structure message'
        );

        return [
            [
                $rule,
                'XX29NWBK60161331926819',
                [new Error('Custom Invalid Country message')],
            ],
            [
                $rule,
                'GB99NWBK60161331926819',
                [new Error('Custom Invalid Checksum message')],
            ],
            [
                $rule,
                'GB12BARC20201530093A59',
                [new Error('Custom Invalid Structure message')],
            ],
        ];
    }

    protected function getValidator(): RuleHandlerInterface
    {
        return new IbanHandler();
    }
}
