<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Facades;

use Illuminate\Support\Facades\Facade;

class Storage extends Facade
{
    protected static function getFacadeAccessor() {
        return 'storage';
    }
}