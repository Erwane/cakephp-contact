<?php

namespace Contact\Model\Entity;

use Cake\Utility\Inflector;
use Contact\Utility\Phone;

trait ContactEntityTrait
{

    private $_fieldsName = [
        'phones' => [
            'phone',
            'tel',
            'telephone',
            'mobile',
            'mobile_phone',
            'landing_line',
            'portable',
        ],
    ];

    private $_fields = [];

    public function __construct(array $properties = [], array $options = [])
    {
        return parent::__construct($properties, $options);
    }

    public function set($property, $value = null, array $options = [])
    {
        $isString = is_string($property);
        if ($isString && $property !== '') {
            $p = [$property => $value];
        }
        else
        {
            $p = $property;
        }

        foreach($p as $k => &$v)
        {
            if ($this->propertyIsPhone($k))
            {
                $v = $this->__setPhone($v);
            }
        }

        return parent::set($p, null, $options);
    }

    public function __setPhone($phone)
    {
        return Phone::format($phone, ['format' => 'short']);
    }

    protected function propertyIsPhone($property)
    {
        if (empty($this->_fields['phones']))
        {
            $this->__setPhoneKeys();
        }

        return array_search($property, $this->_fields['phones']);
    }

    private function __setPhoneKeys()
    {
        $keys = [];
        foreach ($this->_fieldsName['phones'] as $key) {
            $inflectored = [
                Inflector::camelize($key),
                Inflector::dasherize($key),
                Inflector::underscore($key),
                Inflector::variable($key),
            ];

            foreach ($inflectored as $field)
            {
                // Default Version
                $keys[$field] = $field;

                // strtolower
                $keys[strtolower($key)] = strtolower($key);

                // strtoupper
                $keys[strtoupper($key)] = strtoupper($key);
            }
        }

        $this->_fields['phones'] = $keys;
    }
}
