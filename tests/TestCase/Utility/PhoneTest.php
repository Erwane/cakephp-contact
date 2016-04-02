<?php

namespace Contact\Test\TestCase\Utility;

use Contact\Utility\Phone;
use Cake\TestSuite\TestCase;

class PhoneTest extends TestCase
{
    public function testPhoneFormatFr()
    {
        $country = 'FR';
        $this->assertEquals('+33 1 23 45 67 89', Phone::format('01.23.45.67.89', ['country' => $country]));
        $this->assertEquals('01 23 45 67 89', Phone::format('01.23.45.67.89', ['format' => 'national', 'country' => $country]));
        $this->assertEquals('tel:+33-1-23-45-67-89', Phone::format('01.23.45.67.89', ['format' => 'uri', 'country' => $country]));
        $this->assertEquals('+33123456789', Phone::format('01.23.45.67.89', ['format' => 'short', 'country' => $country]));
    }

    public function testPhoneFormatEn()
    {
        $country = 'GB';
        $this->assertEquals('+44 7795 841283', Phone::format('07-795-841-283', ['country' => $country]));
        $this->assertEquals('07795 841283', Phone::format('07-795-841-283', ['format' => 'national', 'country' => $country]));
        $this->assertEquals('tel:+44-7795-841283', Phone::format('07-795-841-283', ['format' => 'uri', 'country' => $country]));
        $this->assertEquals('+447795841283', Phone::format('07-795-841-283', ['format' => 'short', 'country' => $country]));
    }
}
