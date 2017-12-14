<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Repository;

use Cai\Foundation\Repository;

class UserAchievementRepository extends Repository
{
    protected $_table = 'user_achievement';

    protected $_connection = 'user';

    public function get($uid, $type, $achievementId)
    {
        return $this->table()->where([
            ['uid', $uid],
            ['type', $type],
            ['achievement_id', $achievementId],
        ])->first();
    }

    public function create($uid, $type, $achievementId, $now, $isComplete=false)
    {
        return $this->table()->insert([
            'uid' => $uid,
            'type'=> $type,
            'achievement_id' => $achievementId,
            'now' => $now,
            'is_complete' => $isComplete,
        ]);
    }

    public function update($uid, $type, $achievementId, $now, $isComplete=false)
    {
        return $this->table()->where([
            ['uid', $uid],
            ['type', $type],
            ['achievement_id', $achievementId],
        ])->update([
            'now' => $now,
            'is_complete' => $isComplete,
        ]);
    }

    public function updateRewardState($uid, $type, $achievementId, $isTaken = true)
    {
        return $this->table()->where([
            ['uid', $uid],
            ['type', $type],
            ['achievement_id', $achievementId],
        ])->update([
            'is_taken' => $isTaken,
        ]);
    }

    public function isComplete($uid, $type, $achievementId)
    {
        $task = $this->get($uid, $type, $achievementId);
        return $task ? $task->is_complete : false;
    }

}