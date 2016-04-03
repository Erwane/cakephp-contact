# CakePHP 3.x plugin to manipulate contact data
===============================================

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE) [![Travis](https://img.shields.io/travis/Erwane/cakephp-contact.svg?style=flat-square)](https://travis-ci.org/Erwane/cakephp-contact)

CakePHP-Contact is here to help you save, test and display all locality and contact datas like phone & address.

It is compatible with CakePHP 3 only.

- [Installation](#installing-with-composer)
    - [Configuration](#configuration)
- [Usage](#usage)
    - [Validation](#validation)
    - [Entity](#entity-saving-data)
    - [Views](#views)

# Installing with Composer

The package is available on [Packagist](https://packagist.org/packages/erwane/cakephp-contact).
You can install it using this [Composer](http://getcomposer.org) command in the root of your project:

```bash
composer require erwane/cakephp-contact
```

```php
# In config/bootstrap.php
Plugin::load('Contact', ['bootstrap' => true]);
```

If you need Helper:
```php
# In Controller/AppController.php
public $helpers = [
    'Contact' => [ 'className' => 'Contact.Contact' ],
];
```

## Configuration
By default, the EntityTrait treat this fields as:

**Phone**
```sql
`phone`, `tel`, `telephone`, `mobile`, `mobile_phone`, `landing_line`, `portable`
```

**Address part**
```sql
`organization`, `street1`, `street2`, `postalCode`, `locality`, `region`, `country`
```

**Address format**
The address_text virtual field is formated by a string:
<pre>
:organization\n:street1\n:street2\n:postalCode :locality\n:country
</pre>

You can add or override those fields by configuring the plugin
```php
# In config/app.php
    /*
     * Contact Plugin.
     */
    'Contact' => [
        // Address text format
        'addressFormat' => ":organization\n:street1 :street2\n:postalCode :locality\n:country"

        // Fields options
        'fields' => [
            // exclusive set to true override default configuration instead of merging.
            'exclusive' => true,

            // phone fields
            'phone' => [ 'tel', 'customer_phone' ],

            // address fields binding
            'address' => [
                // 'key' => 'myFieldName',
                'organization' => 'title',
                'street1' => 'Adresse',
                'street2' => 'ComplementAdresse',
                'postalCode' => 'CodePostal',
                'locality' => 'Commune',
                // You can fetch field from another table if exists
                'region' => 'Regions.title',
                'country' => 'Countries.title',
            ],
        ],
    ],
```

# Usage

## Validation

## Entity
You can add 3 really easy to use contact manipulation by using ContactEntityTrait in your Entity file
```php
class User extends Entity
{
    # add this to use ContactEntityTrait
    use \Contact\Model\Entity\ContactEntityTrait;
```

Your phone fields will be formated before save.

You can access two new Entity property :
- \$entity->address_full : formated array of entity address + google microformat
- \$entity->address_text : string formated of entity address (configurable)

## Views
You can format a phone number in a really simple manner;

```php
echo $this->Contact->phone($entity->phone);
```
