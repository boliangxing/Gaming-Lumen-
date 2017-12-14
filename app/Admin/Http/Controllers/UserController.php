<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Admin\Http\Controllers;

use App\Admin\Repository\UserRepository;
use Cai\Foundation\BackendController;

class UserController extends BackendController
{
    /**
     * @var UserRepository
     */
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    public function search()
    {
        $users = $this->repository->search($this->request->query());

        return $this->data($users);
    }

    public function info()
    {
        $this->validate($this->request, [
            'uid' => 'required|integer',
        ],[
            'uid.required' => '请选择禁用用户',
            'uid.integer' => '用户ID错误',
        ]);

        $user = $this->repository->getById($this->request->query('uid'));

        return $this->data($user);
    }

    public function ban()
    {
        $this->validate($this->request, [
            'uid' => 'required|integer',
            'option' => 'required|in:0,1,2,3,4,5',
        ],[
            'uid.required' => '请选择禁用用户',
            'uid.integer' => '用户ID错误',
            'option.required' => '参数错误',
            'option.in' => '选项参数错误',
        ]);

        $this->repository->ban($this->request->input('uid'), $this->request->input('option'));

        return $this->success();
    }
}