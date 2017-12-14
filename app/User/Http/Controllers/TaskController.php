<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Http\Controllers;

use Cai\Foundation\Controller;
use App\User\Repository\UserTaskRepository;

class TaskController extends Controller
{
    protected $config;
    protected $taskList;
    protected $repository;

    public function __construct(UserTaskRepository $userTaskRepository)
    {
        $this->config = require __DIR__.'/../../config/task.php';
        $this->taskList = $this->config['taskList'];
        $this->repository = $userTaskRepository;

        parent::__construct();
    }

    public function getList()
    {
        $list = $this->taskList;
        $uid = $this->getUserId();

        foreach ($list as $taskId => &$task)
        {
            $task['des'] = sprintf($task['des'], $task['goal']);
            $taskData = $this->repository->get($uid, $taskId);
            if ($taskData){
                $task['now'] = $taskData->now;
                $task['is_complete'] = $taskData->is_complete;
                $task['is_taken'] = $taskData->is_taken;
            }
            else{
                $task['now'] = 0;
                $task['is_complete'] = 0;
                $task['is_taken'] = 0;
            }
        }


        return $this->success($list);
    }

    public function getReward()
    {
        $taskId = $this->request->input('id');
        $uid = $this->getUserId();
        if(!$this->repository->isComplete($uid, $taskId))
            return $this->fail('领取奖励失败, 任务还未完成');

        $taskData = $this->repository->get($uid, $taskId);
        if ($taskData && $taskData->is_taken)
            return $this->fail('不能重复领取奖励');

        $taskConfig = $this->taskList[$taskId];
        $reward = $taskConfig['reward'];
        //TODO:发奖励

        $this->repository->updateRewardState($uid, $taskId);
        return $this->success([], '领取奖励成功');
    }

}