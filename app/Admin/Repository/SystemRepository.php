<?php
/**
 *
 * Copyright (c) 2017.  收菜网
 * Date: 2017/10/19
 * Time: 11:10
 */
namespace App\Admin\Repository;

use Cai\Foundation\Repository;

class SystemRepository extends Repository
{
    protected $_table = 'system';
    protected $_connection = 'system';

    public function getSettings($keyname)
    {
        $data = $this->table()->where('keyname','=',$keyname)->value('data');
        if($data){
            return json_decode($data,true);
        }
        return $data;
    }

    public function saveSettings($keyname,array $data)
    {
        $data = json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        return $this->table()->updateOrInsert(['keyname'=>$keyname],['data'=>$data]);
    }
}