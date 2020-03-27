<?php
declare(strict_types=1);

namespace Contact\Test\TestCase\Utility;

use Cake\TestSuite\TestCase;
use Contact\Utility\Phone;
use InvalidArgumentException;

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

    /**
     * @test
     */
    public function phoneFormatNoOptions()
    {
        $phone = Phone::format('01.23.45.67.89');
        self::assertEquals('+33 1 23 45 67 89', $phone);
    }

    /**
     * @test
     */
    public function phoneFormatOptions()
    {
        $phone = Phone::format('07-795-841-283', ['country' => 'GB']);
        self::assertSame('+44 7795 841283', $phone);

        $phone = Phone::format('0123456789', ['format' => 'uri']);
        self::assertSame('tel:+33-1-23-45-67-89', $phone);
    }
}
