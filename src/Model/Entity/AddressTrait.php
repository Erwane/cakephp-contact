<?php
declare(strict_types=1);

namespace Contact\Model\Entity;

use Cake\Datasource\EntityInterface;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\Utility\Text;

/**
 * Trait AddressTrait
 *
 * @package Contact\Model\Entity
 * @property array $address_fields
 * @property array $address_full
 * @property string $address_text
 * @property string $address_format
 */
trait AddressTrait
{
    /**
     * @var array<string>
     */
    private array $defaultAddressFields = [
        'organization' => 'organization',
        'street1' => 'street1',
        'street2' => 'street2',
        'postalCode' => 'postalCode',
        'locality' => 'locality',
        'region' => 'Regions.title', // Pluralized table name
        'country' => 'Countries.title', // Pluralized table name
    ];

    /**
     * Address format.
     * This string use Text::insert() method to format address as you wish.
     * You can override it in your entity or with setAddressFormat(string $format) method
     *
     * @var string
     */
    private string $defaultAddressFormat = ":organization\n:street1\n:street2\n:postalCode :locality\n:country";

    private array $currentAddressFields = [];
    private string $currentAddressFormat = '';

    /**
     * @var array
     */
    private array $_addressContents = [
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

    /**
     * Get address fields from current, entity $_addressFields or $defaultAddressFields
     *
     * @return array|array<string>
     */
    protected function _getAddressFields(): array
    {
        if (!empty($this->currentAddressFields)) {
            return $this->currentAddressFields;
        } elseif (isset($this->_addressFields) && is_array($this->_addressFields)) {
            return $this->_addressFields;
        } else {
            return $this->defaultAddressFields;
        }
    }

    /**
     * Define which fields are formated
     *
     * @param array $fields Fields description
     * @param bool $merge Merge fields or not. True by default.
     * @return $this
     */
    public function setAddressFields(array $fields, bool $merge = true)
    {
        // Not empty
        if (!empty($fields)) {
            // With merge
            if ($merge) {
                $fields = Hash::merge($this->_getAddressFields(), $fields);
            }
            // Else no merge ? $fields is set
        } else {
            // Empty fields ? set default
            $fields = $this->_getAddressFields();
        }

        $this->currentAddressFields = $fields;

        return $this;
    }

    /**
     * Get address format from $defaultAddressFormat, $_addressFormat or currentAddressFormat
     *
     * @return string
     */
    protected function _getAddressFormat(): string
    {
        if (!empty($this->currentAddressFormat)) {
            return $this->currentAddressFormat;
        } elseif (isset($this->_addressFormat) && is_string($this->_addressFormat)) {
            return $this->_addressFormat;
        } else {
            return $this->defaultAddressFormat;
        }
    }

    /**
     * Set address format
     *
     * @param string $format Address format. Use colon (:) for name
     * @return $this
     */
    public function setAddressFormat(string $format)
    {
        if (str_contains($format, ':')) {
            $this->currentAddressFormat = $format;
        } else {
            $this->currentAddressFormat = $this->_getAddressFormat();
        }

        return $this;
    }

    /**
     * Get the address in text format
     *
     * @return string
     */
    protected function _getAddressText(): string
    {
        return Text::insert($this->_getAddressFormat(), $this->address_full);
    }

    /**
     * Accessor trying to format address based on entity datas. If no address data, set to [].
     * address_full set to array or empty array if no data
     *
     * @return array
     */
    protected function _getAddressFull(): array
    {
        $address = $this->_addressContents;

        // parse fieldsname to find address data
        foreach ($this->_getAddressFields() as $k => $field) {
            $value = null;
            if (str_contains($field, '.')) {
                [$entity, $field] = explode('.', $field);
                $association = Inflector::underscore(Inflector::singularize($entity));
                if ($this->{$association} instanceof EntityInterface) {
                    $value = $this->{$association}->{$field};
                }
            } else {
                $value = $this->{$field};
            }

            // Format value
            if ($value === null) {
                $value = '';
            } elseif (is_object($value) && method_exists($value, '__toString')) {
                $value = $value->__toString();
            } elseif (is_scalar($value)) {
                $value = (string)$value;
            } elseif (is_array($value)) {
                $value = json_encode($value);
            }

            // Affect data to right key
            switch ($k) {
                case 'organization':
                    $address['organization'] = $value;
                    break;
                case 'street1':
                    $address['street1'] = $value;
                    $address['microformat']['streetAddress'] .= $value;
                    break;
                case 'street2':
                    $address['street2'] = $value;
                    if ($value) {
                        if ($address['microformat']['streetAddress']) {
                            $address['microformat']['streetAddress'] .= PHP_EOL;
                        }
                        $address['microformat']['streetAddress'] .= $value;
                    }
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

        return $address;
    }
}
