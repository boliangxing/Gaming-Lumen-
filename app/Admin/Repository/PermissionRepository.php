<?php
/**
 *
 * Copyright (c) 2017.  收菜网
 * Date: 2017/10/20
 * Time: 10:54
 */
namespace App\Admin\Repository;

use Cai\Foundation\Repository;

class PermissionRepository extends Repository
{
    protected $_table = 'permission';
    protected $_connection = 'system';

}