<?php
/**
 *
 * Copyright (c) 2017.  收菜网
 * Date: 2017/10/23
 * Time: 18:20
 */
namespace App\Admin\Repository\Crawler;

use Cai\Foundation\Repository;

class LeagueRepository extends Repository
{
    protected $_table = 'league';
    protected $_connection = 'crawler';

    public function insertAll($data)
    {
        return $this->table()->insert($data);
    }
}