<?php

namespace Contact\View\Helper;

use Cake\View\Helper;
use Contact\Utility\Phone;

class ContactHelper extends Helper
{
    public function phone($text)
    {
        return Phone::format($text);
    }
}
