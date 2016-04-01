<?php

use Cake\Core\Configure;
use Cake\Utility\Hash;

$defaultFields = [
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

$fields = $defaultFields;

debug(Configure::read('Contact'));

if (!empty(Configure::read('Contact.fields')))
{
    if (Configure::read('Contact.fields.mergePolicy') === 'exclusive')
    {
        $fields = Configure::read('Contact.fields');
    }
    else
    {
        $fields = Hash::merge($defaultFields, Configure::read('Contact.fields'));
    }
}

Configure::write('Contact.fields', $fields);

debug(Configure::read('Contact'));
exit;
