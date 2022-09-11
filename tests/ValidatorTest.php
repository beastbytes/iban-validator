<?php
/**
 * @copyright Copyright © 2022 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace Tests;

use BeastBytes\Iban\Rule\Iban;
use PHPUnit\Framework\TestCase;
use Tests\Stub\DataSet;
use Tests\Stub\FakeValidatorFactory;

class ValidatorTest extends TestCase
{
    public function test_good_ibans(): void
    {
        $ibans = array_keys(require __DIR__ . '/testIbans.php');
        $validator = FakeValidatorFactory::make();

        foreach ($ibans as $iban) {
            $dataObject = new DataSet(compact('iban'));

            $result = $validator->validate($dataObject, [
                'iban' => [new Iban()]
            ]);

            $this->assertTrue($result->isAttributeValid('iban'));
        }
    }
    public function test_bad_ibans(): void
    {
        $ibans = require __DIR__ . '/badIbans.php';
        $validator = FakeValidatorFactory::make();

        foreach ($ibans as $iban) {
            $dataObject = new DataSet(compact('iban'));

            $result = $validator->validate($dataObject, [
                'iban' => [new Iban()]
            ]);

            $this->assertFalse($result->isAttributeValid('iban'));
        }
    }
}