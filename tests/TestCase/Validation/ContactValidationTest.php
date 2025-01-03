<?php
declare(strict_types=1);

namespace Contact\Test\TestCase\Validation;

use Cake\TestSuite\TestCase;
use Contact\Validation\ContactValidation;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

/**
 * ContactValidation tests.
 */
#[UsesClass(ContactValidation::class)]
#[CoversClass(ContactValidation::class)]
class ContactValidationTest extends TestCase
{
    public function testPhoneEmpty()
    {
        $this->assertFalse(ContactValidation::phone(''));
    }

    public function testPhoneWithCountry()
    {
        $this->assertTrue(ContactValidation::phone('020 1234 5678', 'GB'));
    }

    public function testPhoneInternational()
    {
        $this->assertTrue(ContactValidation::phone('+44 20 1234 5678'));
    }
}
