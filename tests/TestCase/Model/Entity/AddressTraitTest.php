<?php
declare(strict_types=1);

namespace Contact\Test\TestCase\Model\Entity;

use Cake\Chronos\Chronos;
use Cake\ORM\Entity as CakeEntity;
use Cake\TestSuite\TestCase;
use Contact\TestApp\Model\Entity\Entity;
use Contact\TestApp\Model\Entity\EntityCustom;

/**
 * Class AddressTraitTest
 *
 * @package Contact\Test\TestCase\Model\Entity
 * @coversDefaultClass \Contact\Model\Entity\AddressTrait
 */
class AddressTraitTest extends TestCase
{
    /**
     * @var \Contact\TestApp\Model\Entity\Entity|\PHPUnit\Framework\MockObject\MockObject
     */
    private $entity;

    private $data = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->data = [
            'organization' => 'Erwane Breton',
            'street1' => '123 rue de la liberté',
            'street2' => 'Arrière cours',
            'postalCode' => '01234',
            'locality' => 'St Jean des corbières',
            'country' => new CakeEntity(['id' => 1, 'title' => 'France']),
        ];

        $this->entity = new Entity($this->data);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entity = null;
    }

    /**
     * @test
     * @covers ::_getAddressFields
     */
    public function testGetAddressFieldsDefault()
    {
        self::assertSame([
            'organization' => 'organization',
            'street1' => 'street1',
            'street2' => 'street2',
            'postalCode' => 'postalCode',
            'locality' => 'locality',
            'region' => 'Regions.title',
            'country' => 'Countries.title',
        ], $this->entity->address_fields);
    }

    /**
     * @test
     * @covers ::_getAddressFields
     */
    public function testGetAddressFieldsFromEntity()
    {
        $entity = new EntityCustom([]);
        self::assertSame([
            'organization' => 'NomSociete',
            'street1' => 'AdresseSociete',
            'street2' => 'ComplementAdresse',
            'postalCode' => 'CodePostal',
            'locality' => 'Ville',
            'region' => 'Region',
            'country' => 'Pays',
        ], $entity->address_fields);
    }

    /**
     * @test
     * @covers ::setAddressFields
     */
    public function testSetAddressFieldsEmpty()
    {
        $entity = $this->entity->setAddressFields([]);
        self::assertSame([
            'organization' => 'organization',
            'street1' => 'street1',
            'street2' => 'street2',
            'postalCode' => 'postalCode',
            'locality' => 'locality',
            'region' => 'Regions.title',
            'country' => 'Countries.title',
        ], $this->entity->address_fields);
        self::assertSame($entity, $this->entity);
    }

    /**
     * @test
     * @covers ::setAddressFields
     * @covers ::_getAddressFields
     */
    public function testSetAddressFieldsOverwrite()
    {
        $this->entity->setAddressFields(['key' => 'testing'], false);
        self::assertSame(['key' => 'testing'], $this->entity->address_fields);
    }

    /**
     * @test
     * @covers ::setAddressFields
     */
    public function testSetAddressFieldsMerge()
    {
        $this->entity->setAddressFields(['key' => 'testing']);
        self::assertArrayHasKey('organization', $this->entity->address_fields);
        self::assertArrayHasKey('key', $this->entity->address_fields);
    }

    /**
     * @test
     * @covers ::_getAddressFormat
     */
    public function testGetAddressFormatDefault()
    {
        self::assertSame(":organization\n:street1\n:street2\n:postalCode :locality\n:country", $this->entity->address_format);
    }

    /**
     * @test
     * @covers ::_getAddressFormat
     */
    public function testGetAddressFormatFromEntity()
    {
        $entity = new EntityCustom([]);
        self::assertSame(":street1 :street2\n:locality :postalCode\n:region :country", $entity->address_format);
    }

    /**
     * @test
     * @covers ::setAddressFormat
     * @covers ::_getAddressFormat
     */
    public function testSetAddressFormatSuccess()
    {
        $this->entity->setAddressFormat(':organization');
        self::assertSame(':organization', $this->entity->address_format);
    }

    /**
     * @test
     * @covers ::setAddressFormat
     */
    public function testSetAddressFormatNoColon()
    {
        $this->entity->setAddressFormat('nocolon');
        self::assertSame(":organization\n:street1\n:street2\n:postalCode :locality\n:country", $this->entity->address_format);
    }

    /**
     * @test
     * @covers ::_getAddressFull
     */
    public function testGetAddressFull()
    {
        $address = $this->entity->address_full;

        self::assertEquals('Erwane Breton', $address['organization']);
        self::assertEquals('123 rue de la liberté', $address['street1']);
        self::assertEquals('Arrière cours', $address['street2']);
        self::assertEquals('01234', $address['postalCode']);
        self::assertEquals('St Jean des corbières', $address['locality']);
        self::assertEquals('France', $address['country']);

        self::assertCount(5, $address['microformat']);
        self::assertEquals('St Jean des corbières', $address['microformat']['addressLocality']);
    }

    /**
     * @test
     * @covers ::_getAddressText
     */
    public function testGetAddressText()
    {
        $this->entity->set($this->data);

        self::assertEquals(
            "Erwane Breton\n123 rue de la liberté\nArrière cours\n01234 St Jean des corbières\nFrance",
            $this->entity->address_text
        );
    }

    /**
     * @test
     * @covers ::_getAddressText
     */
    public function testCustomAddressFormatFromClass()
    {
        $entity = new EntityCustom([
            'NomSociete' => 'Erwane Breton',
            'AdresseSociete' => '123 rue de la liberté',
            'ComplementAdresse' => 'Arrière cours',
            'CodePostal' => '01234',
            'Ville' => 'Seattle',
            'Region' => 'WA',
            'Pays' => 'USA',
        ]);

        self::assertEquals(
            "123 rue de la liberté Arrière cours\nSeattle 01234\nWA USA",
            $entity->address_text
        );
    }

    /**
     * @test
     * @covers ::_getAddressFull
     */
    public function testGetAddressFullObject()
    {
        $this->entity->set(['organization' => Chronos::parse('2021-01-26 12:34:56')]);
        self::assertSame('2021-01-26 12:34:56', $this->entity->address_full['organization']);
    }

    /**
     * @test
     * @covers ::_getAddressFull
     */
    public function testGetAddressFullArray()
    {
        $organization = ['title' => 'testing'];
        $this->entity->set(['organization' => $organization]);
        self::assertJson($this->entity->address_full['organization']);
    }
}
