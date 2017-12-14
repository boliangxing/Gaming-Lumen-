<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Repository;

use Cai\Foundation\Repository;
use Cache;

//日常任务存储在缓存里？
class UserDailyTaskRepository //extends Repository
{
    //protected $_table = 'user_task';

    //protected $_connection = 'user';

    public function get($uid, $taskId)
    {
        $key = $this->getKey($uid, $taskId);
        $task = Cache::get($key);
        if ($task){
            $task = json_decode($task);
            return $task->data == date('Ymd') ? $task : null;
        }
        else{
            return null;
        }
    }

    public function create($uid, $taskId, $now, $isComplete=false)
    {
        $key = $this->getKey($uid, $taskId);
        $data = [
            'now' => $now,
            'is_complete' => $isComplete,
            'data' => date('Ymd'),
            'is_taken' => false,
        ];
        Cache::put($key, json_encode($data), 24*60);
        return true;
    }

    public function update($uid, $taskId, $now, $isComplete = false)
    {
        $key = $this->getKey($uid, $taskId);
        $data = [
            'now' => $now,
            'is_complete' => $isComplete,
            'data' => date('Ymd'),
            'is_taken' => false,
        ];
        Cache::put($key, json_encode($data), 24*60);
        return true;
    }

    public function updateRewardState($uid, $taskId, $isTaken = true)
    {
        $task = $this->get($uid, $taskId);
        if(!$task) return false;
        //$task = json_decode($task);
        $data = [
            'now' => $task->now,
            'is_complete' => $task->is_complete,
            'data' => $task->data,
            'is_taken' => $isTaken,
        ];
        $key = $this->getKey($uid, $taskId);
        Cache::put($key, json_encode($data), 24*60);
        return true;
    }

    public function isComplete($uid, $taskId)
    {
        $task = $this->get($uid, $taskId);
        return $task ? $task->is_complete : false;
    }

    protected function getKey($uid, $taskId)
    {
        return sprintf('daily_task_%d_%d', $uid, $taskId);
    }


}