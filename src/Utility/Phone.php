<?php

namespace Contact\Utility;

use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class Phone
{

    protected static $formats = [
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
     *      'country' => 'FR',
     *      'format' => 'international',
     *  ]
     * @return formatted phone number
     */
    public static function format($text, $options = [])
    {
        if ($text !== '') {
            $options = array_merge(
                [
                'country' => 'FR',
                'format' => 'international',
                ],
                $options
            );

            $LibPhone = PhoneNumberUtil::getInstance();

            if ($LibPhone->isViablePhoneNumber($text)) {
                $phone = $LibPhone->parse($text, $options['country']);
                $text = $LibPhone->format($phone, self::$formats[$options['format']]);
            } else {
                $text = '';
            }
        }

        return $text;
    }
}
