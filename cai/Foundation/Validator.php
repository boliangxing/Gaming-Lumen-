<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Foundation;

class Validator
{
    public static function isMobile($value, $countryCode = '')
    {
        return preg_match('/^(\+?0?86\-?)?((13\d|14[57]|15[^4,\D]|17[3678]|18\d)\d{8}|170[059]\d{7})$/', $value) == 1;
    }
}