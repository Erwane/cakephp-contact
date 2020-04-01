
# CakePHP 4.x plugin to manipulate contact data
===============================================

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE) [![Travis](https://img.shields.io/travis/Erwane/cakephp-contact.svg?style=flat-square)](https://travis-ci.org/Erwane/cakephp-contact)

CakePHP-Contact is here to help you save, test and display all locality and contact datas like phone & address.

It is compatible with CakePHP 4 only.
For Cakephp3 compatibility, use 1.x versions

- [Installation](#installing-with-composer)
    - [Configuration](#configuration)
- [Usage](#usage)
    - [Validation](#validation)
    - [Entity](#entity-saving-data)
    - [Views](#views)

## Installing with Composer

Use composer to install it
```bash
composer require erwane/cakephp-contact
```

## Utility

**Contact\Utility\Phone::format**(?string $text = null, array $options = [])

`$text` is string or null
`$options` is an array with this possible options and output
* `country` : a country code like `FR` or `UK`
* `format` :
        * `international`: +33 1 23 45 67 89
        * `national`: 01 23 45 67 89
        * `uri`: tel:+33-1-23-45-67-89
        * `short`: +33123456789


## PhoneNumberType
The phone number database type automatically format request data to an E164 phone number (+33....)
It also format phone number from unformated database result.

### How to use PhoneNumberType

```php
// in src/Application.php
use Cake\Core\Exception\MissingPluginException;

public function bootstrap(): void
{
    // Load Contact plugin
    try {
        $this->addPlugin(\Contact\Plugin::class);
    } catch (MissingPluginException $e) {
        debug($e->getMessage());
    }
}

// in table file
use Cake\Database\Schema\TableSchemaInterface;
use Cake\Orm\Table;

class UsersTable extends Table
    /**
     * {@inheritDoc}
     */
    protected function _initializeSchema(TableSchemaInterface $schema): TableSchemaInterface
    {
        $schema->setColumnType('phone_number', 'phonenumber')
            ->setColumnType('mobile_number', 'phonenumber');

        return $schema;
    }
```

### Default country
Phone number in forms are set in the user country format, like `0123456789` for France. But there can be conflict, depends of the user Country who fill the form.
You can set `defaultCountry` for all phone number not set in international format.

```php
// in config/bootstrap.php
// or after loaded user preference or website country
use Cake\Database\TypeFactory;
use Contact\Database\Type\PhoneNumberType;

$phoneNumberType = new PhoneNumberType();
$phoneNumberType->setDefaultCountry('BE');
TypeFactory::set('phonenumber', $phoneNumberType);
```
Now, all non international form phone numbers was formated with +32 prefix


## Validation

Contact plugin provide a simple phone number validation rule
```php
// in validation method

public function validationDefault(Validator $validator)
{
    $validator->setProvider('contact', 'Contact\Validation\ContactValidation');
    $validator->add('phone_number', [
        'number' => [
            'provider' => 'contact',
            'rule' => ['phone'],
        ],
    ]);
}

// You can pass country argument
$validator->add('phone_number', [
    'number' => [
        'provider' => 'contact',
        'rule' => ['phone', 'ES'],
    ],
]);
```

## View Helper

You can format a phone number in a really simple manner;

```php
// In src/AppView.php
public function initialize(): void
{
    $this->loadHelper('Contact.Contact');
}

// in template file
echo $this->Contact->phone($entity->phone_number);

// Can pass options (see Utility/Phone::format() help)
echo $this->Contact->phone($entity->phone_number, [
    'country' => 'BE',
    'format' => 'uri',
]);
```
