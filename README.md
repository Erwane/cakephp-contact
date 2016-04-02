# CakePHP 3.x plugin to manipulate contact data
===============================================

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE) [![Travis](https://img.shields.io/travis/Erwane/cakephp-contact.svg?style=flat-square)](https://travis-ci.org/Erwane/cakephp-contact) [![Coverage Status](https://img.shields.io/codecov/c/github/Erwane/cakephp-contact.svg?style=flat-square)](https://codecov.io/github/Erwane/cakephp-contact)

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
By default, the EntityTrait treat this fields as "phone fields"
- phone
- tel
- telephone
- mobile
- mobile_phone
- landing_line
- portable

You can add or override those fields by configuring the plugin
```php
# In config/app.php
    /*
     * Contact Plugin.
     */
    'Contact' => [
        'fields' => [
            'mergePolicy' => 'exclusive',
            'phone' => [ 'tel', 'customer_phone' ],
        ]
    ],
```

Available options:
<pre>
Contact.fields.mergePolicy (string):
'merge' (default): Add your fields to default fields
'exclusive': Use only your fields

Contact.fields.phone (array): The fields name you want be treated as phone
</pre>

# Usage

## Validation

## Entity (saving data)
You can format your phone information by using ContactEntityTrait in your Entity.
Just add this line after your Entity declaration:
```php
class UserEntity extends Entity
{
    # add this to use ContactEntityTrait
    use \Contact\Model\Entity\ContactEntityTrait;
```


## Views
You can format a phone number in a relly simple manner;

```php
echo $this->Contact->phone($entity->phone);
```
