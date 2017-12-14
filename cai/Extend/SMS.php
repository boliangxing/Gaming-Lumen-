<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Extend;

use Cai\Exceptions\VerificationException;
use Flc\Dysms\Client;
use Flc\Dysms\Request\SendSms;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SMS
{
    private $client;

    protected $config;

    public function __construct()
    {
        $app = app();
        $this->config = $app['config']['sms'];
        $opts = [
            'accessKeyId' => $this->config['app_id'],
            'accessKeySecret' => $this->config['secret_token']
        ];
        $this->client = new Client($opts);
    }

    /**
     * 发送注册验证码
     * @param $phone 手机号
     * @param $code 验证码
     */
    public function sendRegisterCode($phone,$code)
    {
        $templateId = $this->config['templates']['register'];
        return $this->send($phone,$templateId,$code);
    }

    /**
     * 发送登录验证码
     * @param $phone 手机号
     * @param $code 验证码
     */
    public function sendLoginCode($phone,$code)
    {
        $templateId = $this->config['templates']['login'];
        return $this->send($phone,$templateId,$code);
    }

    /**
     * 发送修改密码验证码
     * @param $phone 手机号
     * @param $code 验证码
     */
    public function sendModifyPwdCode($phone,$code)
    {
        $templateId = $this->config['templates']['update_password'];
        return $this->send($phone,$templateId,$code);
    }


    /**
     * 发送短信验证码
     *
     * 短信验证码 ：使用同一个签名，对同一个手机号码发送短信验证码，1条/分钟，5条/小时，10条/天。
     *
     * @param $phone 手机号
     * @param $templateId 模板ID
     * @param $code 验证码
     */
    public function send($phone,$templateId,$code)
    {
        $send = new SendSms();
        $send->setPhoneNumbers($phone);
        $send->setSignName($this->config['sign_name']);
        $send->setTemplateCode($templateId);
        $send->setTemplateParam(['code'=>$code]);
        //$send->setOutId();
        $res = $this->client->execute($send);
        if($res->Code == 'OK'){
            return true;
        }
        Log::error('' . $phone . ' send fail ' . $res->Code . ':' . $res->Message);
        return $this->returnError($res->Code);
    }

    /**
     * 验证短信验证码
     *
     * @param $phone
     * @param $type
     * @param $code
     * @return bool
     */
    public static function verifyCode($phone, $type, $code)
    {
        switch($type) {
            case 'register':
                $key = 'sms_register_' . $phone;
                break;
            case 'login':
                $key = 'sms_login_' . $phone;
                break;
            case 'modify_password':
                $key = 'sms_modify_password_' . $phone;
                break;
            default:
                throw new VerificationException('类型错误');
        }

        $oldCode = \Cache::get($key);
        if($oldCode == $code){
            \Cache::forget($key);

            return true;
        }

        return false;
    }

    protected function returnError($error_code)
    {
        $error = ['code'=>'OK','message'=>''];
        switch($error_code){
            case 'isp.RAM_PERMISSION_DENY':
                $error = ['code'=>$error_code,'message'=>'RAM权限DENY'];
                break;
            case 'isv.OUT_OF_SERVICE':
                $error = ['code'=>$error_code,'message'=>'业务停机'];
                break;
            case 'isv.SMS_TEMPLATE_ILLEGAL':
                $error = ['code'=>$error_code,'message'=>'短信模板不合法'];
                break;
            case 'isv.SMS_SIGNATURE_ILLEGAL':
                $error = ['code'=>$error_code,'message'=>'短信签名不合法'];
                break;
            case 'isv.INVALID_PARAMETERS':
                $error = ['code'=>$error_code,'message'=>'参数异常'];
                break;
            case 'isv.MOBILE_NUMBER_ILLEGAL':
                $error = ['code'=>$error_code,'message'=>'非法手机号'];
                break;
            case 'isv.MOBILE_COUNT_OVER_LIMIT':
                $error = ['code'=>$error_code,'message'=>'手机号码数量超过限制'];
                break;
            case 'isv.BLACK_KEY_CONTROL_LIMIT':
                $error = ['code'=>$error_code,'message'=>'黑名单管控'];
                break;
            case 'isv.AMOUNT_NOT_ENOUGH':
                $error = ['code'=>$error_code,'message'=>'账户余额不足'];
                break;
            case 'isv.BUSINESS_LIMIT_CONTROL':
                $error = ['code'=>$error_code,'message'=>'业务限流'];
                break;
            default:
                $error = ['code'=>$error_code,'message'=>'短信发送失败'];
        }
        return $error;
    }

    public function checkNumberLimitByPhone($phone)
    {
        $key = md5($phone);
        $count = Cache::get($key,0,60*24);
        if($count<3) {
            Cache::increment($key);
            return true;
        }
        return false;
    }

    public function checkNumberLimitByIp($ip)
    {
        $key = md5($ip);
        $count = Cache::get($key,0,60*24);
        if($count<3) {
            Cache::increment($key);
            return true;
        }
        return false;
    }
}