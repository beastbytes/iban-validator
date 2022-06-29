<?php

declare(strict_types=1);

namespace BeastBytes\Iban\Rule;

use Closure;
use JetBrains\PhpStorm\ArrayShape;
use Yiisoft\Validator\BeforeValidationInterface;
use Yiisoft\Validator\ParametrizedRuleInterface;
use Yiisoft\Validator\Rule\Trait\HandlerClassNameTrait;
use Yiisoft\Validator\Rule\Trait\BeforeValidationTrait;
use Yiisoft\Validator\Rule\Trait\RuleNameTrait;
use Yiisoft\Validator\ValidationContext;

final class Iban implements ParametrizedRuleInterface, BeforeValidationInterface
{
    use BeforeValidationTrait;
    use HandlerClassNameTrait;
    use RuleNameTrait;

    public function __construct(
        private string $invalidChecksumMessage = 'Checksum not valid',
        private string $invalidCountryMessage = 'Country code "{country}" not valid',
        private string $invalidStructureMessage = 'IBAN structure not valid for country "{country}"',
        private bool $skipOnEmpty = false,
        private bool $skipOnError = false,
        /**
         * @var Closure(mixed, ValidationContext):bool|null
         */
        private ?Closure $when = null,
    ) {
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
        'invalidChecksumMessage' => 'string',
        'invalidCountryMessage' => 'array',
        'invalidStructureMessage' => 'array',
        'skipOnEmpty' => 'bool',
        'skipOnError' => 'bool',
    ])]
    public function getOptions(): array
    {
        return [
            'invalidChecksumMessage' => $this->invalidChecksumMessage,
            'invalidCountryMessage' => [
                'message' => $this->invalidCountryMessage,
                'parameters' => [
                ],
            ],
            'invalidStructureMessage' => [
                'message' => $this->invalidStructureMessage,
                'parameters' => [
                ],
            ],
            'skipOnEmpty' => $this->skipOnEmpty,
            'skipOnError' => $this->skipOnError,
        ];
    }
}
