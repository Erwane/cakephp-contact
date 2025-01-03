<?php
declare(strict_types=1);

namespace Contact\TestApp\Model\Entity;

use Cake\ORM\Entity;
use Contact\Model\Entity\AddressTrait;

/**
 * Class EntityCustom
 */
class EntityCustom extends Entity
{
    use AddressTrait;

    protected array $_addressFields = [
        'organization' => 'NomSociete',
        'street1' => 'AdresseSociete',
        'street2' => 'ComplementAdresse',
        'postalCode' => 'CodePostal',
        'locality' => 'Ville',
        'region' => 'Region',
        'country' => 'Pays',
    ];

    protected string $_addressFormat = ":street1 :street2\n:locality :postalCode\n:region :country";
}
