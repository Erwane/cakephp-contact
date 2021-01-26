<?php
declare(strict_types=1);

namespace Contact\TestApp\Model\Entity;

use Contact\Model\Entity\AddressTrait;

/**
 * Class Entity
 *
 * @package Contact\TestApp\Model\Entity
 */
class Entity extends \Cake\ORM\Entity
{
    use AddressTrait;
}
