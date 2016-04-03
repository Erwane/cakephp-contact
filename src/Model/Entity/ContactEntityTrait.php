<?php

namespace Contact\Model\Entity;

use Cake\Core\Configure;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\Utility\Text;
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

        // Address fields.
        // You can define an external model by using Tables.fieldname . Pluralized table name
        'address' => [
            'organization' => 'organization',
            'street1' => 'street1',
            'street2' => 'street2',
            'postalCode' => 'postalCode',
            'locality' => 'locality',
            'region' => 'Regions.title', // Pluralized table name
            'country' => 'Countries.title', // Pluralized table name
        ],
    ];

    private $address = [
        'organization' => '',
        'street1' => '',
        'street2' => '',
        'locality' => '',
        'postalCode' => '',
        'region' => '',
        'country' => '',
        'microformat' => [
            'streetAddress' => '',
            'postalCode' => '',
            'addressLocality' => '',
            'addressRegion' => '',
            'addressCountry' => '',
        ],
    ];

    private $_addressFormat = ":organization\n:street1\n:street2\n:postalCode :city\n:country";

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
        $this->setAddressFormat();
        parent::__construct($properties, $options);
    }

    /**
     * Define which fields are formated
     * @param array $options [description]
     * @return void
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

    public function setAddressFormat($format = null)
    {
        if (is_string($format) && strpos($format, ':') !== false) {
            return $this->_addressFormat = $format;
        } elseif (Configure::read('Contact.addressFormat') !== null) {
            return $this->_addressFormat = Configure::read('Contact.addressFormat');
        }

        return false;
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

    /**
     * Get the formatted address of an entity.
     * @param  array or int $input data
     *         (array)$input: try to format those inputs datas
     *         (int)$input: get this Model address
     * @return [type]         [description]
    public function getAddress($input = null)
    {

    }
    */

    /**
     * Get the address in text format
     * @param  string $separator used for fields
     * @return string
     */
    public function _getAddressText($separator = "\n")
    {
        return Text::insert($this->_addressFormat, $this->address_full);
    }

    /**
     * Accessor trying to format address based on entity datas. If no address data, set to [].
     * address_full set to array or empty array if no data
     */
    public function _getAddressFull()
    {
        if (empty($this) || empty($this->_fieldsName['address']))
        {
            return [];
        }

        $address = $this->address;

        $hasData = false;
        // parse fieldsname to find address data
        foreach ($this->_fieldsName['address'] as $k => $field)
        {
            $value = null;
            if (strpos($field, '.') !== false) {
                list($entity, $field) = explode('.', $field);
                $association = Inflector::underscore(Inflector::singularize($entity));
                if ($this->$association instanceof \Cake\Datasource\EntityInterface) {
                    $value = $this->$association->$field;
                }
            } else {
                $value = $this->$field;
            }
            if ($value != '') {
                $hasData = true;
            }

            // Affect data to right key
            switch($k)
            {
                case 'organization':
                    $address['organization'] = $value;
                    break;
                case 'street1':
                    $address['street1'] = $value;
                    $address['microformat']['streetAddress'] .= $value;
                    break;
                case 'street2':
                    $address['street2'] = $value;
                    $address['microformat']['streetAddress'] .= $address['microformat']['streetAddress'] == '' ?: PHP_EOL . $value;
                    break;
                case 'locality':
                    $address['locality'] = $value;
                    $address['microformat']['addressLocality'] = $value;
                    break;
                case 'postalCode':
                    $address['postalCode'] = $value;
                    $address['microformat']['postalCode'] = $value;
                    break;
                case 'region':
                    $address['region'] = $value;
                    $address['microformat']['addressRegion'] = $value;
                    break;
                case 'country':
                    $address['country'] = $value;
                    $address['microformat']['addressCountry'] = $value;
                    break;
            }
        }

        if (!$hasData) {
            $address = [];
        }

        return $address;
    }
}
