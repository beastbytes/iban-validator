<?php

declare(strict_types=1);

namespace Tests\Rule;

use BeastBytes\IBAN\PHP\IbanStorage;
use BeastBytes\IBAN\Validator\Rule\Iban;
use BeastBytes\IBAN\Validator\Rule\IbanHandler;
use Yiisoft\Validator\Error;
use Yiisoft\Validator\RuleHandlerInterface;

final class IbanHandlerTest extends AbstractRuleValidatorTest
{
    public function failedValidationProvider(): array
    {
        $rule = new Iban(new IbanStorage(), skipOnError: true);

        return [
            'Invalid country' => [
                $rule,
                'XX29NWBK60161331926819',
                [new Error('Country code "XX" not valid')]
            ],
            'GB invalid account structure - wrong length' => [
                $rule,
                'GB29NWBK6016133192681',
                [new Error('IBAN structure not valid for country "GB"')]
            ],
            'GB invalid account structure - digit in bank code' => [
                $rule,
                'GB29NW8K6O161331926819',
                [new Error('IBAN structure not valid for country "GB"')]
            ],
            'GB invalid account structure - letter in sort code' => [
                $rule,
                'GB29NWBK6O161331926819',
                [new Error('IBAN structure not valid for country "GB"')]
            ],
            'GB invalid bank code structure - letter in account number' => [
                $rule,
                'GB29NWBK60161331926B19',
                [new Error('IBAN structure not valid for country "GB"')]
            ],
            'GB invalid checksum' => [
                $rule,
                'GB99NWBK60161331926819',
                [new Error($rule->getInvalidChecksumMessage())]
            ],
            'MT invalid account structure - wrong length' => [
                $rule,
                'MT84MALT011000012345MTLCAST00S',
                [new Error('IBAN structure not valid for country "MT"')]
            ],
            'MT invalid account structure - digit in bank code' => [
                $rule,
                'MT84MA1T011000012345MTLCAST001S',
                [new Error('IBAN structure not valid for country "MT"')]
            ],
            'MT invalid account structure - letter in branch identifier' => [
                $rule,
                'MT84MALT011O00012345MTLCAST001S',
                [new Error('IBAN structure not valid for country "MT"')]
            ],
            'MT invalid checksum' => [
                $rule,
                'MT88MALT011000012345MTLCAST001S',
                [new Error($rule->getInvalidChecksumMessage())]
            ],
        ];
    }

    public function passedValidationProvider(): array
    {
        $rule = new Iban(new IbanStorage());

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
            new IbanStorage(),
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

    protected function getRuleHandler(): RuleHandlerInterface
    {
        return new IbanHandler($this->getTranslator());
    }
}
