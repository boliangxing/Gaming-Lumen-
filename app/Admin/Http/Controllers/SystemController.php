<?php
/**
 *
 * Copyright (c) 2017.  收菜网
 * Date: 2017/10/19
 * Time: 10:32
 */
namespace App\Admin\Http\Controllers;

use App\Admin\Repository\SystemRepository;
use Cai\Foundation\Controller;

class SystemController extends Controller
{
    public function getSettings()
    {
        $keyname = $this->request->get('keyname');
        if(!$keyname){
            $data = [];
        }else {
            $system = new SystemRepository();
            $data = $system->getSettings($keyname);
        }
        if(empty($data)){
            return $this->result(1,'配置项不存在',[]);
        }
        return $this->result(0,'',$data);
    }

    public function postSettings()
    {
        $keyname = $this->request->get('keyname');
        $data = $this->request->get('data');
        if(empty($keyname)){
            return $this->result(1,'键名不能为空',[]);
        }
        if(!empty($data)){
            $value = json_decode($data,true);
        }else{
            $value = [];
        }
        $system = new SystemRepository();
        $res = $system->saveSettings($keyname,$value);
        if($res){
            return $this->result(0,'数据保存成功',[]);
        }else{
            return $this->result(1,'数据保存失败',[]);
        }
    }

    public function getMenu()
    {
        $app = app();
        $all_menu = $app['config']['admin_menu'];

        return $this->data($all_menu);
    }
}