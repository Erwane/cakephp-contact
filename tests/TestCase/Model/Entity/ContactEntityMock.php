<?php

namespace Contact\Test\TestCase\Model\Entity;

use Cake\ORM\Entity;

class ContactEntityMock extends Entity
{
    use \Contact\Model\Entity\ContactEntityTrait;

    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    /**
     * @param array $properties Entity properties
     * @param array $options Entity options
    public function __construct(array $properties = [], array $options = [])
    {
    }
     */
}
