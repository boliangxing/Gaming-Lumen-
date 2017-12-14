<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Http\Controllers;

use App\User\Repository\MailboxRepository;
use Cai\Foundation\Controller;
use Illuminate\Validation\Rule;

class MessageController extends Controller
{
    protected $repository;

    public function __construct(MailboxRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    public function messages()
    {
        $this->validate($this->request, [
            'cursor' => 'required|min:0|integer',
            'category' => ['required', Rule::in([
                MailboxRepository::CATEGORY_ALL,
                MailboxRepository::CATEGORY_READ,
                MailboxRepository::CATEGORY_UNREAD
            ])],
        ], [
            'cursor.required' => 'cursor参数缺失',
            'cursor.integer' => 'cursor必须为整数',
            'cursor.min' => 'cursor不能小于0',
            'category.required' => '请选择消息类型',
            'category.in' => '消息类型不正确',
        ]);

        $messages = $this->repository->getMessages(
            \Auth::user()->id,
            $this->request->input('category'),
            $this->request->input('cursor')
        );

        return $this->data($messages);
    }
}