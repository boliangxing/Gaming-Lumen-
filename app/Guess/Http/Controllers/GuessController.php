<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Guess\Http\Controllers;

use App\Guess\Events\GuessCai;
use Cai\Foundation\Controller;

class GuessController extends Controller
{
    public function guessCai()
    {
        $this->validate($this->request, [
            'cai' => 'required|integer|min:100',
            'guess_id' => 'required|integer',
            'option' => 'required|integer',
        ], [
            'cai.required' => '请输入菜币值',
            'cai.integer' => '菜币必须是整数',
            'cai.min' => '最小菜币值为100',
        ]);

        // 判断竞猜是否已结束，选项是否正确

        // 用户是否有足够的菜币

        // 记录日志

        event(new GuessCai($this->request->input('guess_id'), \Auth::user()->id, $this->request->input('cai')));
    }

    public function guessCard()
    {

    }

    public function getById()
    {

    }
}