<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\IBAN\Validator\Tests\Support;

use BeastBytes\IBAN\Validator\Rule\IbanHandler;
use Yiisoft\Validator\RuleInterface;

final class NotIban implements RuleInterface
{
    public function getHandler(): string
    {
        return IbanHandler::class;
    }

    public function getName(): string
    {
        return 'notIban';
    }
}
