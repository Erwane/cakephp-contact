<?php
namespace Contact\Model\Table;

use libphonenumber\PhoneNumberUtil;

class Validation
{
    public static function phoneNumber($check, $context)
    {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        $phoneNumberObject = $phoneNumberUtil->parse($check, 'FR');
        return $phoneNumberUtil->isValidNumber($phoneNumberObject);
    }
}
