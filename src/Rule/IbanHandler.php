<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\IBAN\Validator\Rule;

use BeastBytes\IBAN\Helper\Iban as IbanHelper;
use Yiisoft\Validator\Exception\UnexpectedRuleException;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\RuleHandlerInterface;
use Yiisoft\Validator\ValidationContext;

/**
 * Checks that the value is a valid {@link https://www.iban.com/ International Bank Account Number (IBAN)}
 */
final class IbanHandler implements RuleHandlerInterface
{
    public function validate(mixed $value, object $rule, ?ValidationContext $context = null): Result
    {
        if (!$rule instanceof Iban) {
            throw new UnexpectedRuleException(Iban::class, $rule);
        }

        $result = new Result();
        if (!is_string($value)) {
            $result->addError($rule->getIncorrectInputMessage(), [
                'attribute' => $context->getTranslatedAttribute(),
                'type' => get_debug_type($value),
            ]);
        } else {
            $value = strtoupper(str_replace(' ', '', $value));
            $country = substr($value, 0, 2);

            $ibanData = $rule->getIbanData();

            if ($ibanData->hasCountry($country)) {
                if (preg_match($ibanData->getPattern($country), $value) === 0) {
                    $result->addError(
                        $rule->getInvalidStructureMessage(), compact('country')
                    );
                } elseif (IbanHelper::mod97($value) !== 1) {
                    $result->addError(
                        $rule->getInvalidChecksumMessage()
                    );
                }
            } else {
                $result->addError(
                    $rule->getInvalidCountryMessage(),
                    compact('country')
                );
            }
        }

        return $result;
    }
}
