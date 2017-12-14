<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Http\Controllers;

use Cai\Extend\SMS;
use Cai\Foundation\Controller;
use Illuminate\Support\Facades\Cache;

class SmsController extends Controller
{
    /**
     * 发送注册短信
     * @return \Illuminate\Http\JsonResponse
     */
    public function register()
    {
        $phone = $this->request->get('phone');
        if(!$this->checkIsCanSendSms($phone)){
            return $this->result(1,'短信发送数量已达上限',null);
        }
        $code = rand(1000,9999);
        $sms = new SMS();
        $res = $sms->sendRegisterCode($phone,$code);
        if($res===true) {
            Cache::put('sms_register_' . $phone, $code, 30);
            return $this->result(0,'短信发送成功',null);
        }
        return $this->result(1,$res['message'],null);
    }

    /**
     * 发送登录短信
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $phone = $this->request->get('phone');
        if(!$this->checkIsCanSendSms($phone)){
            return $this->result(1,'短信发送数量已达上限',null);
        }
        $code = rand(1000,9999);
        $sms = new SMS();
        $res = $sms->sendLoginCode($phone,$code);
        if($res===true) {
            Cache::put('sms_login_' . $phone, $code, 30);
            return $this->result(0,'短信发送成功',null);
        }
        return $this->result(1,$res['message'],null);
    }

    /**
     * 发送修改密码短信
     * @return \Illuminate\Http\JsonResponse
     */
    public function modifyPassword()
    {
        $phone = $this->request->get('phone');
        if(!$this->checkIsCanSendSms($phone)){
            return $this->result(1,'短信发送数量已达上限',null);
        }
        $code = rand(1000,9999);
        $sms = new SMS();
        $res = $sms->sendModifyPwdCode($phone,$code);
        if($res===true) {
            Cache::put('sms_modify_password_' . $phone, $code, 30);
            return $this->result(0,'短信发送成功',null);
        }
        return $this->result(1,$res['message'],null);
    }

    /**
     * 验证短信
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCode()
    {
        $phone = $this->request->get('phone');
        $code = $this->request->get('code');
        $type = $this->request->get('type');
        $key = '';
        switch($type){
            case 'register':
                $key = 'sms_register_' . $phone;
                break;
            case 'login':
                $key = 'sms_login_' . $phone;
                break;
            case 'modify_password':
                $key = 'sms_modify_password_' . $phone;
                break;
        }
        if(empty($key)){
            return $this->result(1,'类型错误',null);
        }
        $old_code = Cache::get($key);
        if($old_code==$code){
            return $this->result(0,'短信验证成功',null);
        }
        return $this->result(1,'短信验证失败',null);
    }

    protected function checkIsCanSendSms($phone)
    {
        $sms = new SMS();
        $phone_limit = $sms->checkNumberLimitByPhone($phone);
        if(!$phone_limit) return false;
        $ip = $this->request->get('ip',$this->request->ip());
        if($ip){
            $ip_limit = $sms->checkNumberLimitByIp($ip);
            if(!$ip_limit) return false;
        }
        return true;
    }
}