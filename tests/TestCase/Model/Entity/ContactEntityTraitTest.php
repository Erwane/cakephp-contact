<?php

namespace Contact\Test\TestCase\Model\Entity;

use Cake\TestSuite\TestCase;

class ContactEntityTest extends TestCase
{

    public $Entity = null;

    public function setUp()
    {
        parent::setUp();
        $this->Entity = new ContactEntityMock();
    }

    public function testSetPhone()
    {
        $this->assertEquals('+33123455667', $this->Entity->__setPhone('01.23.45.56.67'));
    }

    public function testDefaultSetEntity()
    {
        $this->Entity->phone = '01.23.45.67.89';
        $this->assertEquals('+33123456789', $this->Entity->phone);

        $this->Entity->tel = '01.23.45.67.89';
        $this->assertEquals('+33123456789', $this->Entity->tel);

        $this->Entity->telephone_client = '01.23.45.67.89';
        $this->assertEquals('01.23.45.67.89', $this->Entity->telephone_client);
    }

    public function testSetEntityExclusive()
    {
        $this->Entity->setFields(['phone' => ['phone', 'mobile'], 'exclusive' => true]);

        $this->Entity->mobile = '01.23.45.67.89';
        $this->assertEquals('+33123456789', $this->Entity->mobile);

        $this->Entity->tel = '01.23.45.67.89';
        $this->assertEquals('01.23.45.67.89', $this->Entity->tel);
    }

    public function testSetEntityMerge()
    {
        $this->Entity->setFields(['phone' => ['telephone_client']]);

        $this->Entity->mobile = '01.23.45.67.89';
        $this->assertEquals('+33123456789', $this->Entity->mobile);

        $this->Entity->telephone_client = '01.23.45.67.89';
        $this->assertEquals('+33123456789', $this->Entity->telephone_client);
    }

    public function testArrayProperty()
    {
        $this->Entity->set(['mobile' => '01.23.45.67.89', 'telephone_client' => '01.23.45.67.89']);
        $this->assertEquals('+33123456789', $this->Entity->mobile);
        $this->assertEquals('01.23.45.67.89', $this->Entity->telephone_client);
    }
}
