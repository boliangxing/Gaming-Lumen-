<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Http\Controllers;

use App\User\Mails\RegisterMailable;
use Cai\Foundation\Controller;
use Cai\Foundation\VerificationCode;

class EmailController extends Controller
{
    public function send()
    {
        $this->validate($this->request, [
            'template' => 'required|in:register,bind',
            'email' => 'required|email',
        ], [
            'template.required' => '请求参数错误',
            'template.in' => '请求参数错误',
            'email.required' => '请先填写邮箱',
            'email.email' => '邮箱格式不正确',
        ]);

        $code = VerificationCode::generateEmailCode($this->request->input('email'), $this->request->input('template'));

        $mailer = app('mailer');
        $mailer->alwaysTo($this->request->input('email'));
        $mailer->send(new RegisterMailable(compact('code')));

        return $this->success('邮件发送成功，请登录邮箱查看');
    }
}