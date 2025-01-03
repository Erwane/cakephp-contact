<?php
declare(strict_types=1);

/**
 * CakePHP Contact
 * Copyright (c) Erwane BRETON
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Erwane BRETON
 * @see         https://github.com/Erwane/cakephp-contact
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Contact\View\Helper;

use Cake\View\Helper;
use Contact\Utility\Phone;

/**
 * Class ContactHelper
 */
class ContactHelper extends Helper
{
    /**
     * Convenient method to format phone number
     *
     * @param string|null $phone Phone number
     * @param array $options [ 'country' => 'FR', 'format' => 'international', ]
     * @return string|null Formated phone number
     * @throws \libphonenumber\NumberParseException
     */
    public function phone(?string $phone, array $options = []): ?string
    {
        return Phone::format($phone, $options);
    }
}
