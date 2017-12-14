<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Http\Controllers;

use App\User\Events\UserProfileChanged;
use App\User\Repository\UserCredentialRepository;
use App\User\Repository\UserLoginHistoryRepository;
use App\User\Repository\UserRepository;
use Auth;
use Cai\Exceptions\AuthException;
use Cai\Exceptions\VerificationException;
use Cai\Extend\SMS;
use Cai\Foundation\Controller;
use Cai\Foundation\VerificationCode;
use Cai\Rules\EmailOrMobile;
use Cai\Rules\Password;

class AuthController extends Controller
{
    public function login()
    {
        $mobile = $this->request->input('mobile');
        $password = $this->request->input('password');

        try {
            $token = Auth::attempt(['mobile' => $mobile, 'password' => $password]);
        } catch (AuthException $e) {

            return $this->fail($e->getMessage(), 100);
        }

        return $this->data(['token' => $token]);
    }

    public function logout()
    {
        Auth::logout();

        return $this->success();
    }

    public function register()
    {
        // @todo: captcha关联

        $this->validate($this->request, [
            'username' => ['required', new EmailOrMobile],
            'password' => ['required', 'min:8', 'max:20', new Password],
            're_password' => 'required|same:password',
            'agreement' => 'accepted',
            'code' => 'required|integer|min:1000|max:9999'
        ], [
            'username.required' => '请填写手机号或邮箱',
            'password.required' => '请输入密码',
            'password.min' => '密码至少8个字符',
            'password.max' => '密码最多20个字符',
            're_password.required' => '请再次输入密码',
            're_password.same' => '两次输入的密码不一致',
            'code.required' => '请填写验证码',
            'code.integer' => '验证码格式错误',
            'code.min' => '验证码格式错误',
            'code.max' => '验证码格式错误',
            'agreement.accepted' => '必须同意注册条款才能注册',
        ]);

        $params = $this->request->input();

        $code = $params['code'];
        if (strpos($params['username'], '@') !== false) {
            try {
                $result = VerificationCode::verifyEmailCode($params['username'], $code, 'register');
            } catch (VerificationException $e) {
                return $this->fail($e->getMessage());
            }

            if ($result === false) {
                return $this->fail('验证码错误');
            }

            $email = $params['username'];
            $mobile = '';
            $countryCode = '';

            $registerType = UserProfileChanged::REGISTER_TYPE_EMAIL;
        } else {
            try {
                $result = SMS::verifyCode($params['username'], 'register', $code);
            } catch (VerificationException $e) {
                return $this->fail($e->getMessage());
            }

            if ($result === false) {
                return $this->fail('验证码错误');
            }

            $mobile = $params['username'];
            $email = '';
            $countryCode = '86';

            $registerType = UserProfileChanged::REGISTER_TYPE_MOBILE;
        }

        $properties = [
            'mobile' => $mobile,
            'email' => $email,
            'countryCode' => $countryCode,
            'registerType' => $registerType,
        ];

        $userRepository = new UserRepository();

        $uid = $userRepository->register($params['username']);

        $userCredentialRepository = new UserCredentialRepository();

        $userCredentialRepository->addUserCredentials($uid, $email, $countryCode, $mobile, $params['password']);

        event(new UserProfileChanged(UserProfileChanged::USER_REGISTERED, $uid, $properties));

        return $this->success();
    }

    public function getLoginHistory()
    {
        $this->validate($this->request, [
            'cursor' => 'required|min:0|integer',
        ], [
            'cursor.required' => 'cursor参数缺失',
            'cursor.integer' => 'cursor必须为整数',
            'cursor.min' => 'cursor不能小于0',
        ]);

        $loginHistoryRepository = new UserLoginHistoryRepository();

        return $loginHistoryRepository->getLoginHistory(Auth::user()->id, $this->request->get('cursor'));
    }
}
