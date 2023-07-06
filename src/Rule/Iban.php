<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\IBAN\Validator\Rule;

use BeastBytes\IBAN\IbanDataInterface;
use Closure;
use JetBrains\PhpStorm\ArrayShape;
use Yiisoft\Validator\Rule\Trait\SkipOnEmptyTrait;
use Yiisoft\Validator\Rule\Trait\SkipOnErrorTrait;
use Yiisoft\Validator\Rule\Trait\WhenTrait;
use Yiisoft\Validator\RuleHandlerInterface;
use Yiisoft\Validator\RuleWithOptionsInterface;
use Yiisoft\Validator\SkipOnEmptyInterface;
use Yiisoft\Validator\SkipOnErrorInterface;
use Yiisoft\Validator\ValidationContext;
use Yiisoft\Validator\WhenInterface;

final class Iban implements RuleWithOptionsInterface, SkipOnEmptyInterface, SkipOnErrorInterface, WhenInterface
{
    use SkipOnEmptyTrait;
    use SkipOnErrorTrait;
    use WhenTrait;

    public const INCORRECT_INPUT_MESSAGE = 'Invalid type: "{type}". IBAN must be a string.';
    public const INVALID_CHECKSUM_MESSAGE = 'Checksum not valid.';
    public const INVALID_COUNTRY_MESSAGE = 'Country code "{country}" not valid.';
    public const INVALID_STRUCTURE_MESSAGE = 'IBAN structure not valid for country "{country}".';
    public const NAME = 'iban';

    public function __construct(
        private IbanDataInterface $ibanData,
        private string $incorrectInputMessage = self::INCORRECT_INPUT_MESSAGE,
        private string $invalidChecksumMessage = self::INVALID_CHECKSUM_MESSAGE,
        private string $invalidCountryMessage = self::INVALID_COUNTRY_MESSAGE,
        private string $invalidStructureMessage = self::INVALID_STRUCTURE_MESSAGE,
        /**
         * @var bool|callable|null $skipOnEmpty
         */
        private mixed $skipOnEmpty = null,
        private bool $skipOnError = false,
        /**
         * @var Closure(mixed, ValidationContext):bool|null $when
         */
        private ?Closure $when = null,
    ) {
    }

    public function getIbanData(): IbanDataInterface
    {
        return $this->ibanData;
    }

    public function getIncorrectInputMessage(): string
    {
        return $this->incorrectInputMessage;
    }

    public function getInvalidChecksumMessage(): string
    {
        return $this->invalidChecksumMessage;
    }

    public function getInvalidCountryMessage(): string
    {
        return $this->invalidCountryMessage;
    }

    public function getInvalidStructureMessage(): string
    {
        return $this->invalidStructureMessage;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    #[ArrayShape([
        'ibanData' => IbanDataInterface::class,
        'incorrectInputMessage' => 'array',
        'invalidChecksumMessage' => 'string',
        'invalidCountryMessage' => 'array',
        'invalidStructureMessage' => 'array',
        'skipOnEmpty' => 'bool',
        'skipOnError' => 'bool',
    ])]
    public function getOptions(): array
    {
        return [
            'ibanData' => $this->ibanData,
            'incorrectInputMessage' => [
                'message' => $this->incorrectInputMessage,
                'parameters' => [],
            ],
            'invalidChecksumMessage' => $this->invalidChecksumMessage,
            'invalidCountryMessage' => [
                'message' => $this->invalidCountryMessage,
                'parameters' => [],
            ],
            'invalidStructureMessage' => [
                'message' => $this->invalidStructureMessage,
                'parameters' => [],
            ],
            'skipOnEmpty' => $this->getSkipOnEmptyOption(),
            'skipOnError' => $this->skipOnError,
        ];
    }

    public function getHandler(): string|RuleHandlerInterface
    {
        return IbanHandler::class;
    }
}
