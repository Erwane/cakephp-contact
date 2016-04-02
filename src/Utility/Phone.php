<?php

namespace Contact\Utility;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;

class Phone {

    protected static $formats = [
        'international' => PhoneNumberFormat::INTERNATIONAL,
        'national' => PhoneNumberFormat::NATIONAL,
        'uri' => PhoneNumberFormat::RFC3966,
        'short' => PhoneNumberFormat::E164,
    ];

    /**
        format phone number :
        @options [
            'country' => 'FR',
            'format' => 'international',
        ]
    */
    static public function format($text, $options = [])
    {

        if ((string) $text !== '')
        {
            $options = array_merge([
                'country' => 'FR',
                'format' => 'international',
                ], $options);

            $LibPhone = PhoneNumberUtil::getInstance();

            $phone = $LibPhone->parse((string) $text, $options['country']);
            $text = $LibPhone->format($phone, self::$formats[$options['format']]);
        }

        return $text;
    }
}
