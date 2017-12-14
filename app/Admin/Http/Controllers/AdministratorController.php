<?php
/**
 *
 * Copyright (c) 2017.  收菜网
 * Date: 2017/10/20
 * Time: 11:04
 */
namespace App\Admin\Http\Controllers;

use App\Admin\Repository\AdministratorRepository;
use App\Admin\Repository\RoleRepository;
use Cai\Exceptions\AuthException;
use Cai\Foundation\BackendController;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Validator;

class AdministratorController extends BackendController
{
    public function login()
    {
        $admin_name = $this->request->get('admin_name');
        $admin_password = $this->request->get('admin_password');

        try {
            $token = \Auth::guard('admin')->attempt(['username' => $admin_name, 'password' => $admin_password]);
        } catch (AuthException $e) {
            return $this->fail($e->getMessage());
        }

        return $this->data(['token'=>$token]);
    }

    public function logout()
    {
    }

    public function getInfo()
    {
        $app = app();
        $token = $this->request->get('token');
        $admin = Jwt::decode($token,env('JWT_ADMIN'),['HS256']);
        $administrator = new AdministratorRepository();
        $info = $administrator->getAdministratorInfo($admin->admin_id);

        return $this->data($info);
    }

    public function getList()
    {
        $pageIndex = $this->request->get('page',1);
        $pageSize = $this->request->get('size',10);
        $result = (new AdministratorRepository())->searchResult([],$pageIndex,$pageSize,[]);
        return $this->data($result);
    }

    public function addInfo()
    {
        $allow_fields = ['admin_name','admin_password','email','role_id','nickname','realname','phone'];
        $data = [];
        foreach($allow_fields as $field){
            $data[$field] = $this->request->get($field);
        }
        $rules = [
            'admin_name'=>'required',
            'admin_password'=>'required',
            'role_id'=>'required|integer'
        ];
        $v = Validator::make($data,$rules);

        if($v->fails()){
            return $this->fail('字段参数错误');
        }
        try {
            $res = (new AdministratorRepository())->addAdministrator($data);
            return $this->data(['admin_id' => $res]);
        }catch(\Exception $e){
            return $this->fail('管理员添加失败');
        }
    }

    public function updateInfo()
    {
        $admin_id = $this->request->get('admin_id');
        $allow_fields = ['email','role_id','nickname','realname','phone'];
        $data = [];
        foreach($allow_fields as $field){
            $data[$field] = $this->request->get($field);
        }
        $rules = [
            'role_id'=>'required|integer'
        ];
        $v = Validator::make($data,$rules);

        if($v->fails()){
            return $this->fail('字段参数错误');
        }
        try {
            $res = (new AdministratorRepository())->updateAdministrator($admin_id,$data);
            return $this->success([],'管理员更新成功');
        }catch(\Exception $e){
            return $this->fail('管理员更新失败');
        }
    }

    public function updatePassword()
    {
        $admin_id = $this->request->get('admin_id');
        $admin_password = $this->request->get('admin_password');
        if(empty($admin_password)){
            return $this->fail('密码不能为空');
        }
        $res = (new AdministratorRepository())->updatePassword($admin_id,$admin_password);
        if($res){
            return $this->success([],'修改密码成功');
        }
        return $this->fail('修改密码失败');
    }

    public function getRoleList()
    {
        $result = (new RoleRepository())->getList();
        return $this->data($result);
    }

    public function getRoleInfo()
    {
        $role_id = $this->request->get('role_id');
        $role = (new RoleRepository())->getOne($role_id);
        return $this->data($role);
    }
}