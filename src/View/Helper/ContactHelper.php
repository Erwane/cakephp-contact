<?php

namespace Contact\View\Helper;

use Cake\View\Helper;
use Contact\Utility\Phone;

class ContactHelper extends Helper
{
    public function phone($text, $options = [])
    {
        return Phone::format($text, $options);
    }
}
