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
You can format your phone information by using ContactEntityTrait in your Entity.
Just add this line after your Entity declaration:
```php
class UserEntity extends Entity
{
    # add this to use ContactEntityTrait
    use \Contact\Model\Entity\ContactEntityTrait;
```

Now, all "phone" fields will be formated as international format before saved.
Phone fields are variant of that fieldname:

- phone
- tel
- telephone
- mobile
- mobile_phone
- landing_line
- portable


## Views
You can format a phone number in a relly simple manner;

```php
echo $this->Contact->phone($entity->phone);
```
