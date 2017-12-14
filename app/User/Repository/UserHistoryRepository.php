<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Repository;

use Cai\Foundation\Repository;

class UserHistoryRepository extends Repository
{
    protected $_connection = 'user';

    protected $_table = 'user_histories';

    /**
     * 用户行为历史记录
     *
     * @param $uid
     * @param $behavior
     * @param $operations
     * @return bool
     */
    public function addHistoryLog($uid, $behavior, $operations)
    {
        return $this->table()->insert([
            'uid' => $uid,
            'behavior' => $behavior,
            'operations' => json_encode($operations),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}