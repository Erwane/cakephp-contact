<?php

namespace Contact\View\Helper;

use Cake\View\Helper;
use Contact\Utility\Phone;

class ContactHelper extends Helper
{
    /**
     * @param  string $phone number
     * @param  array $options args
     * @return formated phone
     */
    public function phone($phone, $options = [])
    {
        return Phone::format($phone, $options);
    }
}
