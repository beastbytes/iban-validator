<?php

declare(strict_types=1);

namespace BeastBytes\IBAN\Validator\Tests\Rule;

use BeastBytes\IBAN\PHP\IbanData;
use BeastBytes\IBAN\Validator\Rule\Iban;
use BeastBytes\IBAN\Validator\Tests\assets\NotIban;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;
use Yiisoft\Validator\Exception\UnexpectedRuleException;
use Yiisoft\Validator\RuleWithOptionsInterface;
use Yiisoft\Validator\Validator;

use const PHP_INT_MAX;
use const PHP_INT_MIN;

final class IbanTest extends TestCase
{
    public function test_name(): void
    {
        $rule = new Iban(ibanData: new IbanData());
        $this->assertSame(Iban::NAME, $rule->getName());
    }

    public function test_bad_rule(): void
    {
        $this->expectException(UnexpectedRuleException::class);
        (new Validator())->validate('GB29NWBK60161331926819', new NotIban());
    }

    #[DataProvider('invalidIbanTypeProvider')]
    public function test_invalid_iban_types_fail_validation(mixed $data): void
    {
        $rule = new Iban(ibanData: new IbanData());
        $result = (new Validator())->validate($data, $rule);
        $this->assertNotEmpty($result->getErrorMessagesIndexedByPath());
        $this->assertSame(
            strtr(Iban::INCORRECT_INPUT_MESSAGE, [
                '{type}' => get_debug_type($data)
            ]),
            $result->getErrorMessagesIndexedByPath()[''][0]
        );
    }

    #[DataProvider('optionsProvider')]
    public function test_options(RuleWithOptionsInterface $rule, array $expectedOptions): void
    {
        $this->assertEquals($expectedOptions, $rule->getOptions());
    }

    #[DataProvider('validIbanProvider')]
    public function test_valid_ibans_pass_validation(mixed $data): void
    {
        $result = (new Validator())->validate($data, new Iban(ibanData: new  IbanData()));
        $this->assertEmpty($result->getErrorMessagesIndexedByPath());
    }

    #[DataProvider('invalidIbanProvider')]
    public function test_invalid_ibans_fail_validation(mixed $data, string $message): void
    {
        $result = (new Validator())->validate($data, new Iban(ibanData: new  IbanData()));
        $this->assertNotEmpty($result->getErrorMessagesIndexedByPath());
        $this->assertSame(
            $message,
            $result->getErrorMessagesIndexedByPath()[''][0]
        );
    }

    public static function optionsProvider(): \Generator
    {
        foreach ([
            'options' => [
                new Iban(ibanData: new IbanData()),
                [
                    'incorrectInputMessage' => [
                        'message' => Iban::INCORRECT_INPUT_MESSAGE,
                        'parameters' => [],
                    ],
                    'invalidChecksumMessage' => Iban::INVALID_CHECKSUM_MESSAGE,
                    'invalidCountryMessage' => [
                        'message' => Iban::INVALID_COUNTRY_MESSAGE,
                        'parameters' => [],
                    ],
                    'invalidStructureMessage' => [
                        'message' => Iban::INVALID_STRUCTURE_MESSAGE,
                        'parameters' => [],
                    ],
                    'ibanData' => new  IbanData(),
                    'skipOnEmpty' => false,
                    'skipOnError' => false,
                ],
            ],
        ] as $name => $data) {
            yield $name => $data;
        }
    }

    public static function invalidIbanTypeProvider(): \Generator
    {
        foreach ([
            'array' => [
                [1, 2, 3]
            ],
            'bool' => [
                random_int(0, 9) / 2 === 0
            ],
            'integer' => [
                random_int(PHP_INT_MIN, PHP_INT_MAX)
            ],
            'float' => [
                random_int(PHP_INT_MIN, PHP_INT_MAX) / 100
            ],
            'function' => [
                fn() => random_int(PHP_INT_MIN, PHP_INT_MAX)
            ],
            'object' => [
                new stdClass()
            ],
        ] as $name => $data) {
            yield $name => $data;
        }
    }

    public static function validIbanProvider(): \Generator
    {
        foreach ([
            'Andorra' => ['AD1200012030200359100100'],
            'United Arab Emirates' => ['AE070331234567890123456'],
            'Albania' => ['AL47212110090000000235698741'],
            'Austria' => ['AT611904300234573201'],
            'Azerbaijan, Republic of' => ['AZ21NABZ00000000137010001944'],
            'Bosnia and Herzegovina' => ['BA391290079401028494'],
            'Belgium' => ['BE68539007547034'],
            'Bulgaria' => ['BG80BNBG96611020345678'],
            'Bahrain' => ['BH67BMAG00001299123456'],
            'Brazil' => ['BR1800360305000010009795493C1'],
            'Republic of Belarus' => ['BY13NBRB3600900000002Z00AB00'],
            'Switzerland' => ['CH9300762011623852957'],
            'Costa Rica' => ['CR05015202001026284066'],
            'Cyprus' => ['CY17002001280000001200527600'],
            'Czech Republic' => ['CZ6508000000192000145399'],
            'Germany' => ['DE89370400440532013000'],
            'Denmark' => ['DK5000400440116243'],
            'Dominican Republic' => ['DO28BAGR00000001212453611324'],
            'Estonia' => ['EE382200221020145685'],
            'Spain' => ['ES9121000418450200051332'],
            'Finland' => ['FI2112345600000785'],
            'Faroe Islands' => ['FO6264600001631634'],
            'France' => ['FR1420041010050500013M02606'],
            'United Kingdom' => ['GB29NWBK60161331926819'],
            'Georgia' => ['GE29NB0000000101904917'],
            'Gibraltar' => ['GI75NWBK000000007099453'],
            'Greenland' => ['GL8964710001000206'],
            'Greece' => ['GR1601101250000000012300695'],
            'Guatemala' => ['GT82TRAJ01020000001210029690'],
            'Croatia' => ['HR1210010051863000160'],
            'Hungary' => ['HU42117730161111101800000000'],
            'Ireland' => ['IE29AIBK93115212345678'],
            'Israel' => ['IL620108000000099999999'],
            'Iraq' => ['IQ98NBIQ850123456789012'],
            'Iceland' => ['IS140159260076545510730339'],
            'Italy' => ['IT60X0542811101000000123456'],
            'Jordan' => ['JO94CBJO0010000000000131000302'],
            'Kuwait' => ['KW81CBKU0000000000001234560101'],
            'Kazakhstan' => ['KZ86125KZT5004100100'],
            'Lebanon' => ['LB62099900000001001901229114'],
            'Saint Lucia' => ['LC55HEMM000100010012001200023015'],
            'Liechtenstein (Principality of)' => ['LI21088100002324013AA'],
            'Lithuania' => ['LT121000011101001000'],
            'Luxembourg' => ['LU280019400644750000'],
            'Latvia' => ['LV80BANK0000435195001'],
            'Monaco' => ['MC5811222000010123456789030'],
            'Moldova' => ['MD24AG000225100013104168'],
            'Montenegro' => ['ME25505000012345678951'],
            'Macedonia' => ['MK07250120000058984'],
            'Mauritania' => ['MR1300020001010000123456753'],
            'Malta' => ['MT84MALT011000012345MTLCAST001S'],
            'Mauritius' => ['MU17BOMM0101101030300200000MUR'],
            'Netherlands' => ['NL91ABNA0417164300'],
            'Norway' => ['NO9386011117947'],
            'Pakistan' => ['PK36SCBL0000001123456702'],
            'Poland' => ['PL61109010140000071219812874'],
            'Palestinian Territory, Occupied' => ['PS92PALS000000000400123456702'],
            'Portugal' => ['PT50000201231234567890154'],
            'Qatar' => ['QA58DOHB00001234567890ABCDEFG'],
            'Romania' => ['RO49AAAA1B31007593840000'],
            'Serbia' => ['RS35260005601001611379'],
            'Saudi Arabia' => ['SA0380000000608010167519'],
            'Seychelles' => ['SC18SSCB11010000000000001497USD'],
            'Sweden' => ['SE4550000000058398257466'],
            'Slovenia' => ['SI56263300012039086'],
            'Slovak Republic' => ['SK3112000000198742637541'],
            'San Marino' => ['SM86U0322509800000000270100'],
            'Sao Tome And Principe' => ['ST62349428726753102023453230'],
            'El Salvador' => ['SV62CENR00000000000000700025'],
            'Timor-Leste' => ['TL380080012345678910157'],
            'Tunisia' => ['TN5910006035183598478831'],
            'Turkey' => ['TR330006100519786457841326'],
            'Ukraine' => ['UA213996220000026007233566001'],
            'Virgin Islands, British' => ['VG96VPVG0000012345678901'],
            'Republic of Kosovo' => ['XK051212012345678906'],
        ] as $name => $yield) {
            yield $name => $yield;
        }
    }

    public static function invalidIbanProvider(): \Generator
    {
        foreach ([
            'bad country' => [
                'US213996220000026007233566001',
                strtr(Iban::INVALID_COUNTRY_MESSAGE, ['{country}' => 'US'])
            ],
            'bad checksum' => [
                'RO59AAAA1B31007593840000',
                Iban::INVALID_CHECKSUM_MESSAGE
            ],
            'bad format' => [
                'LB620999000000010019012294',
                strtr(Iban::INVALID_STRUCTURE_MESSAGE, ['{country}' => 'LB'])
            ],
        ] as $name => $data) {
            yield $name => $data;
        }
    }
}
