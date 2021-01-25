<?php
declare(strict_types=1);

namespace Contact\Database\Type;

use Cake\Database\DriverInterface;
use Cake\Database\Type\StringType;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

/**
 * Class PhoneNumberType
 *
 * @package Contact\Database\Type
 */
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
     * @param  string $countryCode New defaut country code.
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
     * @param  mixed $value The value to convert.
     * @param  \Cake\Database\DriverInterface $driver The driver instance to convert with.
     * @return string|null
     * @throws \libphonenumber\NumberParseException
     */
    public function toDatabase($value, DriverInterface $driver): ?string
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
     * @param  mixed $value The value to convert.
     * @param  \Cake\Database\DriverInterface $driver The driver instance to convert with.
     * @return string|null
     * @throws \libphonenumber\NumberParseException
     * @throws \libphonenumber\NumberParseException
     */
    public function toPHP($value, DriverInterface $driver): ?string
    {
        $value = parent::toPHP($value, $driver);

        if ($value === null) {
            return null;
        }

        if (strpos($value, '+') === 0) {
            return $value;
        }

        return $this->_formatPhoneNumber($value);
    }

    /**
     * Format phone number in international short format
     *
     * @param  string $value [description]
     * @return string
     * @throws \libphonenumber\NumberParseException
     * @throws \libphonenumber\NumberParseException
     */
    protected function _formatPhoneNumber(string $value): string
    {
        if (PhoneNumberUtil::isViablePhoneNumber($value)) {
            $instance = PhoneNumberUtil::getInstance();
            $phone = $instance->parse($value, $this->defaultCountry);

            return $instance->format($phone, PhoneNumberFormat::E164);
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function requiresToPhpCast(): bool
    {
        return true;
    }
}
