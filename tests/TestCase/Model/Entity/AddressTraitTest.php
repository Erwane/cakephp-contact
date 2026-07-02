<?php
declare(strict_types=1);

namespace Contact\Test\TestCase\Model\Entity;

use Cake\Chronos\Chronos;
use Cake\ORM\Entity as CakeEntity;
use Cake\TestSuite\TestCase;
use Contact\Model\Entity\AddressTrait;
use Contact\TestApp\Model\Entity\Entity;
use Contact\TestApp\Model\Entity\EntityCustom;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * AddressTrait tests.
 */
#[UsesClass(AddressTrait::class)]
#[CoversClass(AddressTrait::class)]
class AddressTraitTest extends TestCase
{
    /**
     * @var \Contact\TestApp\Model\Entity\Entity|\PHPUnit\Framework\MockObject\MockObject|null
     */
    private MockObject|Entity|null $entity;

    private array $data = [];

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

    public function testGetAddressFieldsDefault()
    {
        $this->assertSame([
            'organization' => 'organization',
            'street1' => 'street1',
            'street2' => 'street2',
            'postalCode' => 'postalCode',
            'locality' => 'locality',
            'region' => 'Regions.title',
            'country' => 'Countries.title',
        ], $this->entity->address_fields);
    }

    public function testGetAddressFieldsFromEntity()
    {
        $entity = new EntityCustom([]);
        $this->assertSame([
            'organization' => 'NomSociete',
            'street1' => 'AdresseSociete',
            'street2' => 'ComplementAdresse',
            'postalCode' => 'CodePostal',
            'locality' => 'Ville',
            'region' => 'Region',
            'country' => 'Pays',
        ], $entity->address_fields);
    }

    public function testSetAddressFieldsEmpty()
    {
        $entity = $this->entity->setAddressFields([]);
        $this->assertSame([
            'organization' => 'organization',
            'street1' => 'street1',
            'street2' => 'street2',
            'postalCode' => 'postalCode',
            'locality' => 'locality',
            'region' => 'Regions.title',
            'country' => 'Countries.title',
        ], $this->entity->address_fields);
        $this->assertSame($entity, $this->entity);
    }

    public function testSetAddressFieldsOverwrite()
    {
        $this->entity->setAddressFields(['key' => 'testing'], false);
        $this->assertSame(['key' => 'testing'], $this->entity->address_fields);
    }

    public function testSetAddressFieldsMerge()
    {
        $this->entity->setAddressFields(['key' => 'testing']);
        $this->assertArrayHasKey('organization', $this->entity->address_fields);
        $this->assertArrayHasKey('key', $this->entity->address_fields);
    }

    public function testGetAddressFormatDefault()
    {
        $this->assertSame(":organization\n:street1\n:street2\n:postalCode :locality\n:country", $this->entity->address_format);
    }

    public function testGetAddressFormatFromEntity()
    {
        $entity = new EntityCustom([]);
        $this->assertSame(":street1 :street2\n:locality :postalCode\n:region :country", $entity->address_format);
    }

    public function testSetAddressFormatSuccess()
    {
        $this->entity->setAddressFormat(':organization');
        $this->assertSame(':organization', $this->entity->address_format);
    }

    public function testSetAddressFormatNoColon()
    {
        $this->entity->setAddressFormat('nocolon');
        $this->assertSame(":organization\n:street1\n:street2\n:postalCode :locality\n:country", $this->entity->address_format);
    }

    public function testGetAddressFull()
    {
        $address = $this->entity->address_full;

        $this->assertEquals('Erwane Breton', $address['organization']);
        $this->assertEquals('123 rue de la liberté', $address['street1']);
        $this->assertEquals('Arrière cours', $address['street2']);
        $this->assertEquals('01234', $address['postalCode']);
        $this->assertEquals('St Jean des corbières', $address['locality']);
        $this->assertEquals('France', $address['country']);

        $this->assertCount(5, $address['microformat']);
        $this->assertEquals('St Jean des corbières', $address['microformat']['addressLocality']);
    }

    public function testGetAddressText()
    {
        $this->entity->patch($this->data);

        $this->assertEquals(
            "Erwane Breton\n123 rue de la liberté\nArrière cours\n01234 St Jean des corbières\nFrance",
            $this->entity->address_text,
        );
    }

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

        $this->assertEquals(
            "123 rue de la liberté Arrière cours\nSeattle 01234\nWA USA",
            $entity->address_text,
        );
    }

    public function testGetAddressFullObject()
    {
        $this->entity->patch(['organization' => Chronos::parse('2021-01-26 12:34:56')]);
        $this->assertSame('2021-01-26 12:34:56', $this->entity->address_full['organization']);
    }

    public function testGetAddressFullArray()
    {
        $organization = ['title' => 'testing'];
        $this->entity->patch(['organization' => $organization]);
        $this->assertJson($this->entity->address_full['organization']);
    }
}
