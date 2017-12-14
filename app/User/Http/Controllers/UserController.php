<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Http\Controllers;

use App\User\Events\UserProfileChanged;
use App\User\Repository\UserCredentialRepository;
use App\User\Repository\UserRepository;
use Cai\Exceptions\VerificationException;
use Cai\Facades\Storage;
use Cai\Foundation\Controller;
use Cai\Foundation\VerificationCode;
use Cai\Rules\Mobile;
use Cai\Rules\Password;
use Cai\Rules\Verification;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function updateAvatar()
    {
        $this->validate($this->request, [
            'avatar' => 'required|mimes:jpeg,jpg,bmp,png|max:1024',
        ], [
            'avatar.required' => '请选择图片',
            'avatar.mimes' => '头像必须是图片',
            'avatar.max' => '头像文件不能超过1M',
        ]);

        $avatarPath = Storage::uploadAvatar($this->request->file('avatar'));

        $repository = new UserRepository();

        $repository->updateAvatar(\Auth::user()->id, $avatarPath);

        event(new UserProfileChanged(UserProfileChanged::AVATAR_CHANGED,
            \Auth::user()->id, ['avatar' => $avatarPath]));

        return $this->data(['avatar' => avatar($avatarPath)]);
    }

    public function updateMobile()
    {
        $this->validate($this->request, [
            'mobile' => ['required', new Mobile],
            'code' => ['required', new Verification],
        ], [
            'mobile.required' => '请输入新手机号',
            'code.required' => '请输入验证码',
        ]);

        $newMobile = $this->request->input('mobile');

        $repository = new UserCredentialRepository;
        if ($repository->hasEmailOrMobileRegistered('mobile', $newMobile)) {
            return $this->fail('该邮箱已绑定其他账号');
        }

        // 用户当前的手机号
        $user = $repository->getById(\Auth::user()->id);
        $currentMobile = $user->mobile;

//        SMS::verifyCode()


        $repository->updateMobile(\Auth::user()->id, $newMobile);

        event(new UserProfileChanged(UserProfileChanged::MOBILE_CHANGED,
            \Auth::user()->id, ['mobile' => $newMobile]));

        return $this->success();
    }

    public function updateEmail()
    {
        $this->validate($this->request, [
            'email' => 'required|email',
            'code' => ['required', new Verification()],
        ], [
            'email.required' => '请输入新邮箱',
            'email.email' => '新邮箱格式不正确',
            'code.required' => '请输入验证码',
        ]);

        $newEmail = $this->request->input('email');

        // 验证原邮箱的验证码
        $repository = new UserCredentialRepository;
        if ($repository->hasEmailOrMobileRegistered('email', $newEmail)) {
            return $this->fail('该邮箱已绑定其他账号');
        }

        // 用户当前的手机号
        $user = $repository->getById(\Auth::user()->id);
        $currentEmail = $user->email;

        try {
            $result = VerificationCode::verifyEmailCode(
                $currentEmail,
                $this->request->input('code'),
                'update'
            );
        } catch (VerificationException $e) {
            return $this->fail($e->getMessage());
        }

        if ($result === false) {
            return $this->fail('验证码错误');
        }

        $repository->updateEmail(\Auth::user()->id, $newEmail);

        event(new UserProfileChanged(UserProfileChanged::EMAIL_CHANGED,
            \Auth::user()->id, ['email' => $newEmail]));

        return $this->success();
    }

    public function updateBio()
    {
        $this->validate($this->request, [
            'bio' => 'max:100',
        ], [
            'bio.max' => '个性签名最多不超过100个字',
        ]);

        $repository = new UserRepository();
        $repository->updateBio(\Auth::user()->id, $this->request->input('bio'));

        event(new UserProfileChanged(UserProfileChanged::BIO_CHANGED,
            \Auth::user()->id, ['bio' => $this->request->input('bio')]));

        return $this->success();
    }

    public function updatePassword()
    {
      
        $this->validate($this->request, [
            'old_password' => ['required', new Password()],
            'new_password' => ['required', new Password()],
            're_password' => ['required', 'same:new_password', new Password()],
        ], [
            'old_password.required' => '请输入旧密码',
            'new_password.required' => '请输入新密码',
            're_password.required' => '请再次输入密码',
            're_password.same' => '两次密码输入不一致',
        ]);

        $oldPassword = $this->request->input('old_password');
        $newPassword = $this->request->input('new_password');

        if ($newPassword == $oldPassword) {
            return $this->fail('新密码与旧密码一致');
        }

        $repository = new UserCredentialRepository;

        if ($repository->checkPassword(\Auth::user()->id, $oldPassword) === false) {
            return $this->fail('旧密码不正确');
        }

        $repository->updatePassword(\Auth::user()->id, $newPassword);

        return $this->success();
    }
}
