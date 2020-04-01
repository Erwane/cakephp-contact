<?php
declare(strict_types=1);

namespace Contact\Test\TestCase\Validation;

use Cake\TestSuite\TestCase;
use Contact\Validation\ContactValidation;

class ContactValidationTest extends TestCase
{
    /**
     * @test
     */
    public function phoneEmpty()
    {
        self::assertFalse(ContactValidation::phone(''));
    }

    /**
     * @test
     */
    public function phoneWithCountry()
    {
        self::assertTrue(ContactValidation::phone('020 1234 5678', 'GB'));
    }

    /**
     * @test
     */
    public function phoneInternational()
    {
        self::assertTrue(ContactValidation::phone('+44 20 1234 5678'));
    }
}
