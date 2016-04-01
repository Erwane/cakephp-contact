<?php

namespace Contact\Model\Entity;

use Cake\Utility\Inflector;
use Contact\Utility\Phone;
use Cake\Core\Configure;

trait ContactEntityTrait
{

    private $_fieldsName = [];

    private $_fields = [];

    public function __construct(array $properties = [], array $options = [])
    {
        $this->_fieldsName = Configure::read('Contact.fields');
        return parent::__construct($properties, $options);
    }

    public function set($property, $value = null, array $options = [])
    {

        // convert into array
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

        // revert to same format
        if ($isString)
        {
            $property = $k;
            $value = $v;
        }
        else
        {
            $property = $p;
        }

        return parent::set($property, $value, $options);
    }

    public function __setPhone($phone)
    {
        return Phone::format($phone, ['format' => 'short']);
    }

    protected function propertyIsPhone($property)
    {
        if (empty($this->_fields['phone']))
        {
            $this->__setPhoneKeys();
        }

        return array_search($property, $this->_fields['phone']);
    }

    private function __setPhoneKeys()
    {
        $keys = [];
        foreach ($this->_fieldsName['phone'] as $key) {
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

        $this->_fields['phone'] = $keys;
    }
}
