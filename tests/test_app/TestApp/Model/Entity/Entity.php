<?php
declare(strict_types=1);

namespace Contact\TestApp\Model\Entity;

use Cake\ORM\Entity as CakeEntity;
use Contact\Model\Entity\AddressTrait;

/**
 * Class Entity
 */
class Entity extends CakeEntity
{
    use AddressTrait;
}
