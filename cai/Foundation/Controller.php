<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Foundation;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @var Request
     */
    protected $request;

    public function __construct()
    {
        $this->request = app('request');
    }

    /**
     * 是否是匿名用户
     *
     * @return bool
     */
    protected function isAnonymous()
    {
        return \Auth::user() === null;
    }

    /**
     * 是否已登录
     *
     * @return bool
     */
    protected function isLogin()
    {
        return !$this->isAnonymous();
    }

    /**
     * 获取用户id
     *
     * @return int
     */
    protected function getUserId()
    {
        return \Auth::id();
    }

    protected function result($code = 0, $message = '', $data)
    {
        return new JsonResponse(['code' => $code, 'message' => $message, 'data' => $data]);
    }

    protected function data($data)
    {
        return $this->result(0, '', $data);
    }

    protected function success($data = [], $message = '')
    {
        return $this->result(0, $message, $data);
    }

    protected function fail($message, $code = -200, $data = null)
    {
        return $this->result($code, $message, $data);
    }
}