<?php

namespace Contact\Test\TestCase\Model\Entity;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;

class ContactEntityTest extends TestCase
{

    public $Entity = null;

    public function setUp()
    {
        parent::setUp();
        $this->Entity = new ContactEntityMock();
    }

    /*
        Phone Tests
     */

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

    /*
        Address Tests
     */

    public function testFullAddressDefault()
    {
        $this->Entity->set([
            'organization' => 'Erwane Breton',
            'street1' => "123 rue de la liberté",
            'street2' => "Arrière cours",
            'postalCode' => '01234',
            'locality' => 'St Jean des corbières',
            'country' => new ContactEntityMock(['id' => 1, 'title' => 'France']),
        ]);

        $address = $this->Entity->address_full;

        $this->assertEquals("Erwane Breton", $address['organization']);
        $this->assertEquals("123 rue de la liberté", $address['street1']);
        $this->assertEquals("Arrière cours", $address['street2']);
        $this->assertEquals("01234", $address['postalCode']);
        $this->assertEquals("St Jean des corbières", $address['locality']);
        $this->assertEquals("France", $address['country']);

        $this->assertCount(5, $address['microformat']);
        $this->assertEquals("St Jean des corbières", $address['microformat']['addressLocality']);
    }

    public function testFullAddressCustom()
    {
        $this->Entity->setFields(['address' => [
            'organization' => 'NomSociete',
            'street1' => 'AdresseSociete',
            'street2' => 'ComplementAdresse',
            'postalCode' => 'CodePostal',
            'locality' => 'Ville',
            'region' => 'Region',
            'country' => 'Pays',
        ]]);

        $this->Entity->set([
            'AdresseSociete' => "123 rue de la liberté",
            'ComplementAdresse' => "Arrière cours",
            'CodePostal' => '01234',
            'Ville' => 'Seattle',
            'Region' => "WA",
            'Pays' => "USA",
        ]);

        $address = $this->Entity->address_full;

        $this->assertEquals("123 rue de la liberté", $address['street1']);
        $this->assertEquals("Arrière cours", $address['street2']);
        $this->assertEquals("01234", $address['postalCode']);
        $this->assertEquals("Seattle", $address['locality']);
        $this->assertEquals("WA", $address['region']);
        $this->assertEquals("USA", $address['country']);

        $this->assertCount(5, $address['microformat']);
        $this->assertEquals("Seattle", $address['microformat']['addressLocality']);
    }

    public function testNoAddressData()
    {
        $result = $this->Entity->address_full;
        $this->assertCount(0, $result);
    }

    public function testNoAddressField()
    {
        $this->Entity->setFields(['exclusive' => true, 'phone' => ['tel']]);
        $result = $this->Entity->address_full;
        $this->assertCount(0, $result);
    }

    public function testTextAddressDefault()
    {
        $this->Entity->set([
            'organization' => 'Erwane Breton',
            'street1' => "123 rue de la liberté",
            'street2' => "Arrière cours",
            'postalCode' => '01234',
            'locality' => 'St Jean des corbières',
            'country' => new ContactEntityMock(['id' => 1, 'title' => 'France']),
        ]);

        $this->assertEquals("Erwane Breton\n123 rue de la liberté\nArrière cours\n01234 St Jean des corbières\nFrance", $this->Entity->address_text);
    }

    public function testTextAddressCustom()
    {
        $this->Entity->set([
            'organization' => 'Erwane Breton',
            'street1' => "123 rue de la liberté",
            'street2' => "Arrière cours",
            'postalCode' => '01234',
            'locality' => 'St Jean des corbières',
            'country' => new ContactEntityMock(['id' => 1, 'title' => 'France']),
        ]);

        $this->Entity->setAddressFormat(":street1 :street2\n:locality :postalCode\n:region :country");

        $this->assertEquals("123 rue de la liberté Arrière cours\nSt Jean des corbières 01234\n France", $this->Entity->address_text);
    }

    public function testNoAddressFormat()
    {
        Configure::delete('Contact.addressFormat');
        $result = $this->Entity->setAddressFormat();
        $this->assertFalse($result);
    }

}
