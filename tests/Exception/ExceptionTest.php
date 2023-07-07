<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\IBAN\Validator\Tests\Exception;

use BeastBytes\IBAN\Validator\Tests\Support\NotIban;
use PHPUnit\Framework\TestCase;
use Yiisoft\Validator\Exception\UnexpectedRuleException;
use Yiisoft\Validator\Validator;

final class ExceptionTest  extends TestCase
{
    public function test_bad_rule(): void
    {
        $this->expectException(UnexpectedRuleException::class);
        (new Validator())->validate('GB29NWBK60161331926819', new NotIban());
    }
}
