<?php
declare(strict_types=1);

namespace Contact\Utility;

use InvalidArgumentException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

/**
 * Class Phone
 *
 * @package Contact\Utility
 */
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
     * @param  string|null $text phone number
     * @param  array $options [ 'country' => 'FR', 'format' => 'international', ]
     * @return string|null Formated phone number
     * @throws \libphonenumber\NumberParseException
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
            throw new InvalidArgumentException('format should be short|uri|national|international');
        }

        if (PhoneNumberUtil::isViablePhoneNumber($text)) {
            $instance = PhoneNumberUtil::getInstance();
            $phone = $instance->parse($text, $options['country']);

            if ($options['format'] == 'national' && $instance->getRegionCodeForNumber($phone) !== $options['country']) {
                $options['format'] = 'international';
            }

            return $instance->format($phone, static::$_formats[$options['format']]);
        }

        return $text;
    }
}
