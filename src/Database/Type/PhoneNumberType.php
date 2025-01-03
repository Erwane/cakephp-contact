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
namespace Contact\Database\Type;

use Cake\Database\Driver;
use Cake\Database\Type\StringType;
use Exception;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

/**
 * Class PhoneNumberType
 */
class PhoneNumberType extends StringType
{
    /**
     * Set the default country of input forms
     * If phone number was write without countryCode (+33)
     * this default country code will be used
     *
     * @var string
     */
    protected string $defaultCountry = 'FR';

    /**
     * Set default country type
     *
     * @param string $countryCode New default country code.
     * @return $this
     * @see https://en.wikipedia.org/wiki/List_of_country_calling_codes
     */
    public function setDefaultCountry(string $countryCode)
    {
        $this->defaultCountry = $countryCode;

        return $this;
    }

    /**
     * Convert string data into phone number international
     *
     * @param mixed $value The value to convert.
     * @param \Cake\Database\Driver $driver The driver instance to convert with.
     * @return string|null
     */
    public function toDatabase(mixed $value, Driver $driver): ?string
    {
        $value = parent::toDatabase($value, $driver);

        if ($value === null) {
            return null;
        }

        return $this->_formatPhoneNumber($value);
    }

    /**
     * Convert string values to PHP strings.
     *
     * @param mixed $value The value to convert.
     * @param \Cake\Database\Driver $driver The driver instance to convert with.
     * @return string|null
     */
    public function toPHP(mixed $value, Driver $driver): ?string
    {
        $value = parent::toPHP($value, $driver);

        if ($value === null) {
            return null;
        }

        if (str_starts_with($value, '+')) {
            return $value;
        }

        return $this->_formatPhoneNumber($value);
    }

    /**
     * Format phone number in international short format
     *
     * @param string $value [description]
     * @return string
     */
    protected function _formatPhoneNumber(string $value): string
    {
        if (PhoneNumberUtil::isViablePhoneNumber($value)) {
            $instance = PhoneNumberUtil::getInstance();
            try {
                $phone = $instance->parse($value, $this->defaultCountry);

                return $instance->format($phone, PhoneNumberFormat::E164);
            } catch (Exception) {
                return $value;
            }
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function requiresToPhpCast(): bool
    {
        return true;
    }
}
