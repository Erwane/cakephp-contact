<?php

namespace Contact\Model\Entity;

use Cake\Core\Configure;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Contact\Utility\Phone;

trait ContactEntityTrait
{

    private $_defaultFields = [
        'phone' => [
            'phone',
            'tel',
            'telephone',
            'mobile',
            'mobile_phone',
            'landing_line',
            'portable',
        ],
    ];

    private $_fieldsName = [];
    private $_fields = [];

    /**
     * @param array $properties Entity properties
     * @param array $options Entity options
     * @return parent::__construct
     */
    public function __construct(array $properties = [], array $options = [])
    {
        $this->setFields();
    }

    /**
     * Define which fields are formated
     * @param array $options [description]
     */
    public function setFields($options = [])
    {
        $fields = !empty($options) ? $options : Configure::read('Contact.fields');

        $newFields = $this->_defaultFields;

        if (!empty($fields)) {
            if (!empty($fields['exclusive'])) {
                $newFields = $fields;
            } else {
                $newFields = Hash::merge($this->_defaultFields, $fields);
            }
        }

        $this->_fieldsName = $newFields;
    }

    /**
     * @param array $property to set
     * @param [value] $value property (or options)
     * @param array $options set
     * @return parent
     */
    public function set($property, $value = null, array $options = [])
    {
        // convert into array
        $isString = is_string($property);
        if ($isString && $property !== '') {
            $p = [$property => $value];
        } else {
            $p = $property;
        }

        foreach ($p as $k => &$v) {
            if ($this->propertyIsPhone($k)) {
                $v = $this->__setPhone($v);
            }
        }

        // revert to same format
        if ($isString) {
            $property = $k;
            $value = $v;
        } else {
            $property = $p;
        }

        return parent::set($property, $value, $options);
    }

    /**
     * @param  string $phone number
     * @return string formated phone
     */
    public function __setPhone($phone)
    {
        return Phone::format($phone, ['format' => 'short']);
    }

    /**
     * @param  string $property name
     * @return bool
     */
    protected function propertyIsPhone($property)
    {
        if (empty($this->_fields['phone'])) {
            $this->__setPhoneKeys();
        }

        return array_search($property, $this->_fields['phone']);
    }

    /**
     * @return void
     */
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

            foreach ($inflectored as $field) {
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
