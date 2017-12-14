<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Guess\Repository;

use Cai\Foundation\Repository;

class GuessLogRepository extends Repository
{
    protected $_connection = 'guess';

    protected $_table = 'guess_logs';

    /**
     * 添加预测行为记录
     *
     * @param $guessId
     * @param $uid
     * @param $guessType
     * @param $behavior
     * @param $desc
     * @return bool
     */
    public function add($guessId, $uid, $guessType, $behavior, $desc)
    {
        return $this->table()->insert([
            'guess_id' => $guessId,
            'uid' => $uid,
            'guess_type' => $guessType,
            'behavior' => $behavior,
            'desc' => $desc,
        ]);
    }
}