<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Repository;

use Cai\Foundation\Repository;

class UserTaskRepository extends Repository
{
    protected $_table = 'user_task';

    protected $_connection = 'user';

    public function get($uid, $taskId)
    {
        return $this->table()->where([
            ['uid', $uid],
            ['task_id', $taskId],
        ])->first();
    }

    public function create($uid, $taskId, $now, $isComplete=false)
    {
        return $this->table()->insert([
            'uid' => $uid,
            'task_id' => $taskId,
            'now' => $now,
            'is_complete' => $isComplete,
        ]);
    }

    public function update($uid, $taskId, $now, $isComplete=false)
    {
        return $this->table()->where([
            ['uid', $uid],
            ['task_id', $taskId],
        ])->update([
            'now' => $now,
            'is_complete' => $isComplete,
        ]);
    }

    public function updateRewardState($uid, $taskId, $isTaken = true)
    {
        return $this->table()->where([
            ['uid', $uid],
            ['task_id', $taskId],
        ])->update([
            'is_taken' => $isTaken,
        ]);
    }

    public function isComplete($uid, $taskId)
    {
        $task = $this->get($uid, $taskId);
        return $task ? $task->is_complete : false;
    }
}