<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Repository;

use Cai\Foundation\Repository;

class UserCheckInRepository extends Repository
{
    protected $_table = 'user_check_in';

    protected $_connection = 'user';

    public function __construct()
    {
        $this->_table = sprintf('user_check_in_%s', date('Ym'));
    }

    public function get($uid)
    {
        $table = $this->table()->where([
            ['uid', $uid]
        ])->first();

        if (!$table){
            $this->create($uid);
            return $this->table()->where([
                ['uid', $uid]
            ])->first();
        }

        return $table;
    }

    protected function create($uid)
    {
        return $this->table()->insert([
            'uid' => $uid,
            'reward_state' => $this->getDefaultRewardState(),
        ]);
    }

    protected function getDefaultRewardState()
    {
        return json_encode([
           '1' => false,
           '2' => false,
           '3' => false,
           '4' => false,
           '5' => false,
           '6' => false,
        ]);
    }

    public function update($uid, $thisMonthCount, $continuousCount, $level, $rank = 0, $date = null)
    {
        if (!$date) $date = date("Y-m-d H:i:s");

        return $this->table()->where([
            ['uid', $uid],
        ])->update([
            'this_month_count' => $thisMonthCount,
            'continuous_count' => $continuousCount,
            'level' => $level,
            'rank' => $rank,
            'last_check_in' => $date,
        ]);
    }

    public function updateRewardState($uid, $level, $isTaken = true)
    {
        $table = $this->get($uid);
        $rewardState = json_decode($table->reward_state, true);
        $rewardState[$level] = $isTaken;
        return $this->table()->where([
            ['uid', $uid]
        ])->update([
            'reward_state' => json_encode($rewardState)
        ]);
    }

}