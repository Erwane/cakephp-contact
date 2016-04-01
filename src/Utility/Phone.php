<?php

namespace Contact\Utility;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;

class Phone {

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
            $formats = [
                'international' => PhoneNumberFormat::INTERNATIONAL,
                'national' => PhoneNumberFormat::NATIONAL,
                'uri' => PhoneNumberFormat::RFC3966,
                'short' => PhoneNumberFormat::E164,
            ];

            $options = array_merge([
                'country' => 'FR',
                'format' => 'international',
                ], $options);

            $LibPhone = PhoneNumberUtil::getInstance();

            $phone = $LibPhone->parse((string) $text, $options['country']);
            $text = $LibPhone->format($phone, $formats[$options['format']]);
        }

        return $text;
    }
}
