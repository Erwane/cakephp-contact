<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.3.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Contact\Test\TestCase\Database\Type;

use Cake\TestSuite\TestCase;
use Contact\Database\Type\PhoneNumberType;

/**
 * Test for the String type.
 */
class PhoneNumberTypeTest extends TestCase
{
    /**
     * @var \Cake\Database\Type\JsonType
     */
    public $type;

    /**
     * @var \Cake\Database\Driver
     */
    public $driver;

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
