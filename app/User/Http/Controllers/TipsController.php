<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Http\Controllers;

use App\Common\Counters\TipsCounter;
use Cai\Foundation\Controller;

class TipsController extends Controller
{
    public function getTips()
    {
        $tipCounter = new TipsCounter();

        $tips = $tipCounter->setKey(\Auth::user()->id)->hasTips('homepage');

        return $this->data($tips);
    }
}