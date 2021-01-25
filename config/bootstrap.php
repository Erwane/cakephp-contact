<?php
declare(strict_types=1);

use Cake\Database\Type;
use Contact\Database\Type\PhoneNumberType;

Type::map('phonenumber', PhoneNumberType::class);
