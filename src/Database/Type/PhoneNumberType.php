<?php
declare(strict_types=1);

namespace Contact\Database\Type;

use Cake\Database\DriverInterface;
use Cake\Database\Type\StringType;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class PhoneNumberType extends StringType
{
    /**
     * Set the default country of input forms
     * If phone number was write without countrycode (+33)
     * this default country code will be used
     *
     * @var string
     */
    protected $defaultCountry = 'FR';

    /**
     * Set defaut country type
     *
     * @param   string $countryCode New defaut country code.
     * @return  self
     * @see     https://en.wikipedia.org/wiki/List_of_country_calling_codes
     */
    public function setDefaultCountry(string $countryCode): self
    {
        $this->defaultCountry = $countryCode;

        return $this;
    }

    /**
     * Convert string data into phone number international
     *
     * @param mixed $value The value to convert.
     * @param \Cake\Database\DriverInterface $driver The driver instance to convert with.
     * @return string|null
     */
    public function toDatabase($value, DriverInterface $driver): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        return $this->_formatPhoneNumber($value);
    }

    /**
     * Convert string values to PHP strings.
     *
     * @param mixed $value The value to convert.
     * @param \Cake\Database\DriverInterface $driver The driver instance to convert with.
     * @return string|null
     */
    public function toPHP($value, DriverInterface $driver): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        if (strpos($value, '+') === 0) {
            return $value;
        }

        return $this->_formatPhoneNumber($value);
    }

    /**
     * Format phone number in international short format
     * @param  string $value [description]
     * @return [type]        [description]
     */
    protected function _formatPhoneNumber(string $value): string
    {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        if ($phoneNumberUtil->isViablePhoneNumber($value)) {
            $phone = $phoneNumberUtil->parse($value, $this->defaultCountry);

            return $phoneNumberUtil->format($phone, PhoneNumberFormat::E164);
        }

        return $value;
    }
}
