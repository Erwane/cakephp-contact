<?php
declare(strict_types=1);

namespace Contact\TestApp\Model\Entity;

use Contact\Model\Entity\AddressTrait;

/**
 * Class EntityCustom
 *
 * @package Contact\TestApp\Model\Entity
 */
class EntityCustom extends \Cake\ORM\Entity
{
    use AddressTrait;

    protected $_addressFields = [
        'organization' => 'NomSociete',
        'street1' => 'AdresseSociete',
        'street2' => 'ComplementAdresse',
        'postalCode' => 'CodePostal',
        'locality' => 'Ville',
        'region' => 'Region',
        'country' => 'Pays',
    ];

    protected $_addressFormat = ":street1 :street2\n:locality :postalCode\n:region :country";
}
