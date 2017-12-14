<?php
/**
 * Copyright (c) 2017.  收菜网
 * Date: 2017/10/19
 * Time: 17:19
 */

namespace App\Shop\Listeners;

use App\Shop\Repository\ShopDebrisRepository;
use App\Shop\Events\ShopProfileChanged;


class DebrisSubscriber
{

    //protected $config;
    protected $productList;
    protected $repository;

    public function __construct(ShopDebrisRepository $shopDebrisRepository)
    {
        $config = require __DIR__.'/../../Conf/product.php';
        $this->productList = $config['productList'];
        $this->repository = $shopDebrisRepository;

    }

    /**
     * 为订阅者注册监听器。
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
//            'App\Test\Events\TestTaskEvent',
            'App\Shop\Listeners\DebrisSubscriber@onTest'
        );
    }

//    public function onTest($event)
//    {
//        //一个事件可能影响到多个任务
//        do{
//            $taskId = 2;
//            //1.检查任务是否完成？
//            if ($this->repository->isComplete($this->getUserId(), $taskId)) break;
//            //2.处理数据*(主要逻辑)
//            $add = 3;
//            //3.处理任务
//            $this->handle($taskId, $add);
//        }while(0);
//
//    }

    protected function handle($taskId, $add)
    {
        $task = $this->getTask($taskId);
        $taskConfig = $this->taskList[$taskId];

        if ($task){
            $now = $task->now + $add;
            if($now >= $taskConfig['goal']){
                if($this->completeTask($taskId, $taskConfig['goal'])){
                    event(new UserMissionCompleted(UserMissionCompleted::TASK, $this->getUserId(),
                        ['taskId' => $taskId]));
                }
            }
            else{
                if($this->updateTask($taskId, $now)){

                }
            }
        }
        else{
            if($add >= $taskConfig['goal']){
                if($this->createTask($taskId, $taskConfig['goal'], true)){
                    event(new UserMissionCompleted(UserMissionCompleted::TASK, $this->getUserId(),
                        ['taskId' => $taskId]));
                }
            }
            else{
                if($this->createTask($taskId, $add)){

                }
            }
        }
    }

    protected function getUserId()
    {
        return 1;
    }

    protected function getTask($taskId)
    {
        $uid = $this->getUserId();
        return $this->repository->get($uid, $taskId);
    }

    protected function createTask($taskId, $now, $isComplete=false)
    {
        $uid = $this->getUserId();
        return $this->repository->create($uid, $taskId, $now, $isComplete);
    }

    protected function updateTask($taskId, $now)
    {
        $uid = $this->getUserId();
        return $this->repository->update($uid, $taskId, $now);
    }

    protected function completeTask($taskId, $goal)
    {
        $uid = $this->getUserId();
        return $this->repository->update($uid, $taskId, $goal,true);
    }

}