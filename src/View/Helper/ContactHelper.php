<?php
declare(strict_types=1);

namespace Contact\View\Helper;

use Cake\View\Helper;
use Contact\Utility\Phone;

/**
 * Class ContactHelper
 *
 * @package Contact\View\Helper
 */
class ContactHelper extends Helper
{
    /**
     * Convenient method to format phone number
     *
     * @param  string|null $phone Phone number
     * @param  array $options [ 'country' => 'FR', 'format' => 'international', ]
     * @return string|null Formated phone number
     * @throws \libphonenumber\NumberParseException
     */
    public function phone(?string $phone, array $options = []): ?string
    {
        return Phone::format($phone, $options);
    }
}
