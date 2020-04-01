<?php
declare(strict_types=1);

use Contact\Database\Type\PhoneNumberType;
use Cake\Database\Type;

Type::map('phonenumber', PhoneNumberType::class);
