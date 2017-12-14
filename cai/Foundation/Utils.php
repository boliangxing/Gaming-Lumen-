<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Foundation;

class Utils
{
    public static function maskEmail($email)
    {
        list($name, $host) = explode('@', $email);
        return substr($name, 0, 1) . '****@' . $host;
    }

    public static function maskMobile($mobile)
    {
        return substr($mobile, 0, 3) . '****' . substr($mobile, -1, 4);
    }
}