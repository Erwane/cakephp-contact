<?php
declare(strict_types=1);

/**
 * @copyright     Erwane BRETON <erwane@phea.fr>
 * @link         https://github.com/Erwane/cakephp-contact
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Contact\Test\TestCase\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type\JsonType;
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
     * @var \Cake\Database\Type\JsonType|\Contact\Database\Type\PhoneNumberType
     */
    public JsonType|PhoneNumberType $type;

    /**
     * @var \Cake\Database\Driver|\PHPUnit\Framework\MockObject\MockObject
     */
    public Driver|MockObject $driver;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->type = new PhoneNumberType();
        $this->driver = $this->getMockBuilder('Cake\Database\Driver')->getMock();
    }

    /**
     * Test converting to database format
     *
     * @test
     */
    public function toDatabase()
    {
        // null
        self::assertNull($this->type->toDatabase(null, $this->driver));

        // Not phone number
        self::assertSame('abc', $this->type->toDatabase('abc', $this->driver));

        // format with default country
        self::assertSame('+33123456789', $this->type->toDatabase('01.23.45.67.89', $this->driver));

        // default is GB
        $this->type->setDefaultCountry('GB');
        self::assertSame('+442012345678', $this->type->toDatabase('020 1234 5678', $this->driver));
    }

    /**
     * Test converting to php format
     *
     * @test
     */
    public function toPhp()
    {
        self::assertNull($this->type->toPhp(null, $this->driver));

        // Already format
        self::assertSame('+33123456789', $this->type->toPhp('+33123456789', $this->driver));

        // Format to default country
        self::assertSame('+33123456789', $this->type->toPhp('01.23.45.67.89', $this->driver));

        // default is GB
        $this->type->setDefaultCountry('GB');
        self::assertSame('+442012345678', $this->type->toPhp('020 1234 5678', $this->driver));
    }
}
