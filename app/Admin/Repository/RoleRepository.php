<?php
/**
 *
 * Copyright (c) 2017.  收菜网
 * Date: 2017/10/20
 * Time: 10:53
 */
namespace App\Admin\Repository;

use Cai\Foundation\Repository;

class RoleRepository extends Repository
{
    protected $_table = 'role';
    protected $_connection = 'system';

    public function getList()
    {
        return $this->table()->select(['id','role_name'])->get();
    }

    public function getOne($role_id)
    {
        $role = $this->table()->where('id','=',$role_id)->first();
        if($role->has_permission){
            $role->has_permission = json_decode($role->has_permission,true);
        }
        return $role;
    }

}