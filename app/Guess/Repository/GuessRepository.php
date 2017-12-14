<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Guess\Repository;

use Cai\Foundation\Repository;

class GuessRepository extends Repository
{
    protected $_connection = 'guess';

    protected $_table = 'guesses';

    const STATUS_CANCELLED = 0;     // 已取消
    const STATUS_NOT_STARTED = 1;   // 未开始
    const STATUS_PROGRESSING = 2;   // 进行中
    const STATUS_CALCULATING = 3;   // 结算中
    const STATUS_CLEARED = 4;       // 已结算

    /**
     * 获取最新的竞猜列表
     *
     * @param int $gameId
     * @param int $status
     */
    public function getLatest($gameId = 0, $status = self::STATUS_NOT_STARTED)
    {
        // 赛事列表


        // 第一次竞猜
    }

    /**
     * 获取最热的竞猜列表
     *
     * @param int $gameId
     */
    public function getHottest($gameId = 0)
    {

    }
}