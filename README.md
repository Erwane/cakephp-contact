# cakephp-contact
cakephp-contact is here to help you save, test and display all locality and contact datas like phone & address.

It is compatible with CakePHP 3 only.

- [Installation](#installing-with-composer)
- [Usage](#usage)
    - [Validation](#validation)
    - [Entity](#entity-saving-data)
    - [Views](#views)

# Installing with Composer

You can install this package via Composer by running this command in your terminal in the root of your project:

```bash
composer require erwane/cakephp-contact
```

### CakePHP

Modify the `Controller/AppController.php` to include this at the top of class.

```php
public $helpers = [
    'Contact' => [ 'className' => 'Contact.Contact' ],
];
```

# Usage

## Validation

## Entity (saving data)

## Views
You can format a phone number realy simply:

```php
echo $this->Contact->phone($entity->phone);
```

