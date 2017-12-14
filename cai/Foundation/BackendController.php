<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Foundation;

class BackendController extends Controller
{
    /**
     * @var \stdClass  当前登录用户
     */
    protected $user;

    public function __construct()
    {
        parent::__construct();

        $this->user = \Auth::guard('admin')->user();
    }

    protected function getUserId()
    {
        return \Auth::guard('admin')->id();
    }
}