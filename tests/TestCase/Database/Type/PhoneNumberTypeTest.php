<?php
declare(strict_types=1);

namespace Contact\Test\TestCase\Database\Type;

use Cake\Database\Driver;
use Cake\TestSuite\TestCase;
use Contact\Database\Type\PhoneNumberType;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * PhoneNumberType tests.
 */
#[UsesClass(PhoneNumberType::class)]
#[CoversClass(PhoneNumberType::class)]
class PhoneNumberTypeTest extends TestCase
{
    /**
     * @var \Contact\Database\Type\PhoneNumberType
     */
    public PhoneNumberType $type;

    /**
     * @var \Cake\Database\Driver|\PHPUnit\Framework\MockObject\MockObject
     */
    public Driver|MockObject $driver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->type = new PhoneNumberType();
        $this->driver = $this->getMockBuilder('Cake\Database\Driver')->getMock();
    }

    /**
     * Test converting to database format
     */
    public function testToDatabase()
    {
        // null
        $this->assertNull($this->type->toDatabase(null, $this->driver));

        // Not phone number
        $this->assertSame('abc', $this->type->toDatabase('abc', $this->driver));

        // format with default country
        $this->assertSame('+33123456789', $this->type->toDatabase('01.23.45.67.89', $this->driver));

        // default is GB
        $this->type->setDefaultCountry('GB');
        $this->assertSame('+442012345678', $this->type->toDatabase('020 1234 5678', $this->driver));
    }

    /**
     * Test converting to php format
     */
    public function testToPhp()
    {
        $this->assertNull($this->type->toPHP(null, $this->driver));

        // Already format
        $this->assertSame('+33123456789', $this->type->toPHP('+33123456789', $this->driver));

        // Format to default country
        $this->assertSame('+33123456789', $this->type->toPHP('01.23.45.67.89', $this->driver));

        // default is GB
        $this->type->setDefaultCountry('GB');
        $this->assertSame('+442012345678', $this->type->toPHP('020 1234 5678', $this->driver));
    }
}
