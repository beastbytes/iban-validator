<?php
/**
 * @copyright Copyright © 2022 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\IBAN\Validator\Rule;

use BeastBytes\IBAN\Helper\Iban as IbanHelper;
use InvalidArgumentException;
use Yiisoft\Translator\TranslatorInterface;
use Yiisoft\Validator\Exception\UnexpectedRuleException;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\RuleHandlerInterface;
use Yiisoft\Validator\ValidationContext;

/**
 * Checks that the value is a valid {@link https://www.iban.com/ International Bank Account Number (IBAN)}
 */
final class IbanHandler implements RuleHandlerInterface
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function validate(mixed $value, object $rule, ?ValidationContext $context = null): Result
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('IBAN must be a string');
        }

        if (!$rule instanceof Iban) {
            throw new UnexpectedRuleException(Iban::class, $rule);
        }

        $result = new Result();
        $value = strtoupper(str_replace(' ', '', $value));
        $country = substr($value, 0, 2);

        $ibans = $rule->getIbans();

        if (!$ibans->hasCountry($country)) {
            $result->addError(
                $this
                    ->translator
                    ->translate(
                        $rule->getInvalidCountryMessage(), compact('country')
                    )
            );
        } elseif (preg_match($ibans->getPattern($country), $value) === 0) {
            $result->addError(
                $this
                    ->translator
                    ->translate(
                        $rule->getInvalidStructureMessage(), compact('country')
                    )
            );
        } elseif (IbanHelper::mod97($value) !== 1) {
            $result->addError(
                $this
                    ->translator
                    ->translate(
                        $rule->getInvalidChecksumMessage()
                    )
            );
        }

        return $result;
    }
}
