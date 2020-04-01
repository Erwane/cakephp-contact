<?php
declare(strict_types=1);

namespace Contact\View\Helper;

use Cake\View\Helper;
use Contact\Utility\Phone;

class ContactHelper extends Helper
{
    /**
     * Convenient method to format phone number
     *
     * @param  string $text    Phone number
     * @param  array  $options [ 'country' => 'FR', 'format' => 'international', ]
     * @return string|null Formated phone number
     */
    public function phone($phone, array $options = []): ?string
    {
        return Phone::format($phone, $options);
    }
}
