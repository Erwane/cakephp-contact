<?php
declare(strict_types=1);

/**
 * CakePHP Contact
 * Copyright (c) Erwane BRETON
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Erwane BRETON
 * @see         https://github.com/Erwane/cakephp-contact
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Contact\Validation;

use Exception;
use libphonenumber\PhoneNumberUtil;

/**
 * Class ContactValidation
 */
class ContactValidation
{
    /**
     * Validate a phone with libphonenumber library
     *
     * @param string $check Input phone number
     * @param string $country Country code number
     * @return bool
     */
    public static function phone(string $check, string $country = 'FR'): bool
    {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        try {
            $number = $phoneNumberUtil->parse($check, $country);

            return $phoneNumberUtil->isValidNumber($number);
        } catch (Exception) {
            return false;
        }
    }
}
