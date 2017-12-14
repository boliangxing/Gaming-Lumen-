<?php
/**
 *
 * Copyright (c) 2017.  收菜网
 * Date: 2017/10/23
 * Time: 18:21
 */
namespace App\Admin\Repository\Crawler;

use Cai\Foundation\Repository;

class ScheduleRepository extends Repository
{
    protected $_table = 'schedule';
    protected $_connection = 'crawler';

    public function insertAll($data)
    {
        return $this->table()->insert($data);
    }
}