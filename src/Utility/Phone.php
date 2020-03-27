<?php
declare(strict_types=1);

namespace Contact\Utility;

use InvalidArgumentException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class Phone
{
    protected static $_formats = [
        'international' => PhoneNumberFormat::INTERNATIONAL,
        'national' => PhoneNumberFormat::NATIONAL,
        'uri' => PhoneNumberFormat::RFC3966,
        'short' => PhoneNumberFormat::E164,
    ];

    /**
     * format phone number :
     *
     * @param  string $text    phone number
     * @param  array  $options [ 'country' => 'FR', 'format' => 'international', ]
     * @return string|null Formated phone number
     */
    public static function format(?string $text = null, array $options = []): ?string
    {
        if (empty($text)) {
            return null;
        }

        $options += [
            'country' => 'FR',
            'format' => 'international',
        ];

        if (!array_key_exists($options['format'], static::$_formats)) {
            throw new InvalidArgumentException("format should be short|uri|national|international");
        }

        $phoneNumberUtil = PhoneNumberUtil::getInstance();

        if ($phoneNumberUtil->isViablePhoneNumber($text)) {
            $phone = $phoneNumberUtil->parse($text, $options['country']);

            return $phoneNumberUtil->format($phone, static::$_formats[$options['format']]);
        }

        return $text;
    }
}
