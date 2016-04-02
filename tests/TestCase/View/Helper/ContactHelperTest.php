<?php

namespace Contact\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use Contact\View\Helper\ContactHelper;

class ContactHelperTest extends TestCase
{
    public $helper = null;

    public function setUp()
    {
        parent::setUp();
        $View = new View();
        $this->helper = new ContactHelper($View);
    }

    public function testPhoneFr()
    {
        $country = 'FR';
        $this->assertEquals('+33 1 23 45 67 89', $this->helper->phone('01.23.45.67.89', ['country' => $country]));
        $this->assertEquals('01 23 45 67 89', $this->helper->phone('01.23.45.67.89', ['format' => 'national', 'country' => $country]));
        $this->assertEquals('tel:+33-1-23-45-67-89', $this->helper->phone('01.23.45.67.89', ['format' => 'uri', 'country' => $country]));
        $this->assertEquals('+33123456789', $this->helper->phone('01.23.45.67.89', ['format' => 'short', 'country' => $country]));
    }

    public function testPhoneGb()
    {
        $country = 'GB';
        $this->assertEquals('+44 7795 841283', $this->helper->phone('07-795-841-283', ['country' => $country]));
        $this->assertEquals('07795 841283', $this->helper->phone('07-795-841-283', ['format' => 'national', 'country' => $country]));
        $this->assertEquals('tel:+44-7795-841283', $this->helper->phone('07-795-841-283', ['format' => 'uri', 'country' => $country]));
        $this->assertEquals('+447795841283', $this->helper->phone('07-795-841-283', ['format' => 'short', 'country' => $country]));
    }
}
