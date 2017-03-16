<?php
namespace Contact\Model\Table;

use libphonenumber\PhoneNumberUtil;

class Validation
{
    public static function phoneNumber($check, $context)
    {
        $Util = PhoneNumberUtil::getInstance();
        try {
            $Number = $Util->parse($check, 'FR');
            return $Util->isValidNumber($Number);
        } catch (\Exception $e) {
            return false;
        }
    }
}
