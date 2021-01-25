<?php
declare(strict_types=1);

namespace Contact\Validation;

use Exception;
use libphonenumber\PhoneNumberUtil;

/**
 * Class ContactValidation
 *
 * @package Contact\Validation
 */
class ContactValidation
{
    /**
     * Validate a phone with libphonenumber librairy
     *
     * @param  string $check Input phone number
     * @param  string $country Country code number
     * @return bool
     */
    public static function phone(string $check, string $country = 'FR'): bool
    {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        try {
            $number = $phoneNumberUtil->parse($check, $country);

            return $phoneNumberUtil->isValidNumber($number);
        } catch (Exception $e) {
            return false;
        }
    }
}
