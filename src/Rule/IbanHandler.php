<?php
/**
 * @copyright Copyright © 2022 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\Iban\Rule;

use BeastBytes\Iban\Iban as Helper;
use InvalidArgumentException;
use Yiisoft\Validator\Exception\UnexpectedRuleException;
use Yiisoft\Validator\Formatter;
use Yiisoft\Validator\FormatterInterface;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\RuleHandlerInterface;
use Yiisoft\Validator\ValidationContext;

/**
 * IBAN validator
 *
 * @package Iban
 * @author Chris Yates
 */
final class IbanHandler implements RuleHandlerInterface
{
    private FormatterInterface $formatter;

    public function __construct(?FormatterInterface $formatter = null)
    {
        $this->formatter = $formatter ?? new Formatter();
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

        /** @var array $ibanFormats */
        $ibanFormats = require dirname(__DIR__) . '/ibanFormats.php';

        if (!array_key_exists($country, $ibanFormats)) {
            $result->addError(
                $this->formatter->format(
                    $rule->getInvalidCountryMessage(), compact('country')
                )
            );
        } else {
            if (preg_match('/' . $ibanFormats[$country]['pattern'] . '/', $value) === 0) {
                $result->addError(
                    $this->formatter->format(
                        $rule->getInvalidStructureMessage(), compact('country')
                    )
                );
            }

            if (Helper::mod97($value) !== 1) {
                $result->addError($this->formatter->format($rule->getInvalidChecksumMessage()));
            }
        }

        return $result;
    }
}
