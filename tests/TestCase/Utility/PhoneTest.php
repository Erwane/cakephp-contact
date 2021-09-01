<?php
declare(strict_types=1);

namespace Contact\Test\TestCase\Utility;

use Cake\TestSuite\TestCase;
use Contact\Utility\Phone;
use InvalidArgumentException;

/**
 * @uses \Contact\Utility\Phone
 * @coversDefaultClass \Contact\Utility\Phone
 */
class PhoneTest extends TestCase
{
    /**
     * @test
     */
    public function noPhone()
    {
        self::assertNull(Phone::format(null));
        self::assertNull(Phone::format(''));
    }

    /**
     * @test
     */
    public function notPhoneNumber()
    {
        self::assertSame('testing', Phone::format('testing'));
    }

    /**
     * @test
     */
    public function invalidFormat()
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('format should be short|uri|national|international');
        Phone::format('testing', ['format' => 'testing']);
    }

    public function dataFormat(): array
    {
        return [
            // No options, international
            ['01.23.45.67.89', [], '+33 1 23 45 67 89'],
            // GB country
            ['07-795-841-283', ['country' => 'GB'], '+44 7795 841283'],
            // URI
            ['0123456789', ['format' => 'uri'], 'tel:+33-1-23-45-67-89'],
            // ES number display in international for France
            ['+34620456789', ['format' => 'national'], '+34 620 45 67 89'],
            // Uri not affected by international format
            ['+34620456789', ['format' => 'uri'], 'tel:+34-620-45-67-89'],
        ];
    }

    /**
     * @test
     * @covers ::format
     * @dataProvider dataFormat
     */
    public function testPhoneFormatOptions($source, $options, $expected)
    {
        $phone = Phone::format($source, $options);
        self::assertSame($expected, $phone);
    }
}
