<?php
/**
 *
 * Copyright (c) 2017.  收菜网
 * Date: 2017/10/23
 * Time: 18:22
 */
namespace App\Admin\Repository\Crawler;

use Cai\Foundation\Repository;

class TeamRepository extends Repository
{
    protected $_table = 'team';
    protected $_connection = 'crawler';

    public function insertAll($data)
    {
        return $this->table()->insert($data);
    }
}