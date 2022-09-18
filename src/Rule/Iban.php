<?php
/**
 * @copyright Copyright © 2022 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\IBAN\Validator\Rule;

use BeastBytes\IBAN\IbanDataInterface;
use Closure;
use JetBrains\PhpStorm\ArrayShape;
use Yiisoft\Validator\BeforeValidationInterface;
use Yiisoft\Validator\Rule\Trait\BeforeValidationTrait;
use Yiisoft\Validator\Rule\Trait\RuleNameTrait;
use Yiisoft\Validator\Rule\Trait\SkipOnEmptyTrait;
use Yiisoft\Validator\SerializableRuleInterface;
use Yiisoft\Validator\SkipOnEmptyInterface;
use Yiisoft\Validator\ValidationContext;

final class Iban implements BeforeValidationInterface, SerializableRuleInterface, SkipOnEmptyInterface
{
    use BeforeValidationTrait;
    use RuleNameTrait;
    use SkipOnEmptyTrait;

    public function __construct(
        private IbanDataInterface $ibanData,
        private string $invalidChecksumMessage = 'Checksum not valid',
        private string $invalidCountryMessage = 'Country code "{country}" not valid',
        private string $invalidStructureMessage = 'IBAN structure not valid for country "{country}"',
        /**
         * @var bool|callable|null $skipOnEmpty
         */
        private $skipOnEmpty = null,
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

    #[ArrayShape([
        'ibanData' => IbanDataInterface::class,
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

    public function getHandlerClassName(): string
    {
        return IbanHandler::class;
    }
}
