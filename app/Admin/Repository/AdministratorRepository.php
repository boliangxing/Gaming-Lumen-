<?php
/**
 *
 * Copyright (c) 2017.  收菜网
 * Date: 2017/10/20
 * Time: 10:52
 */
namespace App\Admin\Repository;

use Cai\Foundation\Repository;

class AdministratorRepository extends Repository
{
    protected $_table = 'administrator';
    protected $_connection = 'system';

    const ERROR_LOGIN_NOT_EXISTS = 'admin_login_not_exists';
    const ERROR_LOGIN_PASSWORD_FAIL = 'admin_login_password_fail';
    const ERROR_LOGIN_DISABLED = 'admin_login_disabled';

    public function doLogin($admin_name,$admin_password)
    {
        $admin = $this->table()->where('admin_name','=',$admin_name)
            ->select(['id','admin_name','admin_password','status'])
            ->first();
        if(!$admin){
            return self::ERROR_LOGIN_NOT_EXISTS;
        }
        if(app('hash')->check($admin_password, $admin->admin_password)){
            return self::ERROR_LOGIN_PASSWORD_FAIL;
        }

        if($admin->status==0){
            return self::ERROR_LOGIN_DISABLED;
        }

        return $admin->id;
    }

    public function encodePwd($password)
    {
        return app('hash')->make($password);
    }

    public function getAdministratorInfo($admin_id)
    {
        $fields = ['id','admin_name','role_id','nickname','realname','last_login_time'];
        return $this->table()->where('id','=',$admin_id)->select($fields)->first();
    }

    public function searchResult($search,$pageIndex,$pageSize,$sort)
    {
        $fields = ['id','admin_name','email','role_id','nickname','realname','last_login_time'];
        $query = $this->buildSearchQuery($search)->forPage($pageIndex,$pageSize);
        if(is_array($sort)){
            foreach($sort as $field=>$order){
                if(!in_array($order,['asc','desc']))
                $query = $query->orderBy($field,$order);
            }
        }
        if($fields){
            $query = $query->select($fields);
        }
        return $query->get();
    }

    public function searchCount($search)
    {
        return $this->buildSearchQuery($search)->count();
    }

    protected function buildSearchQuery(array $search)
    {
        $query = $this->table();

        return $query;
    }

    /**
     * 添加数据
     * @param $data
     * @return int
     */
    public function addAdministrator($data)
    {
        if(isset($data['admin_password'])){
            $data['admin_password'] = $this->encodePwd($data['admin_password']);
        }
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->table()->insertGetId($data);
    }

    /**
     * 更新数据
     * @param $id
     * @param $data
     * @return int
     */
    public function updateAdministrator($id,$data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->table()->where('id','=',$id)->update($data);
    }

    public function updatePassword($admin_id,$admin_password)
    {
        $password = $this->encodePwd($admin_password);
        $data = ['admin_password'=>$password,'updated_at'=>date('Y-m-d H:i:s')];
        return $this->table()->where('id','=',$admin_id)->update($data);
    }

}