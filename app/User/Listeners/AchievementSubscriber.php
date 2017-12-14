<?php
/**
 * Copyright (c) 2017.  收菜网
 * Date: 2017/10/19
 * Time: 17:19
 */

namespace App\User\Listeners;

use App\User\Repository\UserAchievementRepository;
use App\User\Events\UserMissionCompleted;

class AchievementSubscriber
{

    //protected $config;
    protected $achievementList;
    protected $repository;
    protected $types;

    public function __construct(UserAchievementRepository $userAchievementRepository)
    {
        $config = require __DIR__.'/../config/achievement.php';

        $this->types = $config['achievementTypes'];
        $this->achievementList = $config['achievementList'];
        $this->repository = $userAchievementRepository;

    }

    /**
     * 为订阅者注册监听器。
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Test\Events\TestTaskEvent',
            'App\User\Listeners\AchievementSubscriber@onTest'
        );

    }

    public function onTest($event)
    {

        do{
            $id = 1;
            $type = $this->types['test'];
            if ($this->repository->isComplete($this->getUserId(), $type, $id)) break;
            //2.处理数据*(主要逻辑)
            $add = 10;
            //3.处理任务
            $this->handle($type, $id, $add);
        }while(0);

    }

    protected function handle($type, $achievementId, $add)
    {
        $achievement = $this->getAchievement($type, $achievementId);
        $achievementConfig = $this->achievementList[$type][$achievementId];

        if ($achievement){
            $now = $achievement->now + $add;
            if($now >= $achievementConfig['goal']){
                if($this->completeAchievement($type, $achievementId, $achievementConfig['goal'])){
                    event(new UserMissionCompleted(UserMissionCompleted::ACHIEVEMENT, $this->getUserId(),
                        ['achievementType' => $type, 'achievementId' => $achievementId]));
                }
            }
            else{
                if($this->updateAchievement($type, $achievementId, $now)){

                }
            }
        }
        else{
            if($add >= $achievementConfig['goal']){
                if($this->createAchievement($type, $achievementId, $achievementConfig['goal'], true)){
                    event(new UserMissionCompleted(UserMissionCompleted::ACHIEVEMENT, $this->getUserId(),
                        ['achievementType' => $type, 'achievementId' => $achievementId]));
                }
            }
            else{
                if($this->createAchievement($type, $achievementId, $add)){

                }
            }
        }
    }

    protected function getUserId()
    {
        return 1;
    }

    protected function getAchievement($type, $achievementId)
    {
        $uid = $this->getUserId();
        return $this->repository->get($uid, $type, $achievementId);
    }

    protected function createAchievement($type, $achievementId, $now, $isComplete=false)
    {
        $uid = $this->getUserId();
        return $this->repository->create($uid, $type, $achievementId, $now, $isComplete);
    }

    protected function updateAchievement($type, $achievementId, $now)
    {
        $uid = $this->getUserId();
        return $this->repository->update($uid, $type, $achievementId, $now);
    }

    protected function completeAchievement($type, $achievementId, $goal)
    {
        $uid = $this->getUserId();
        return $this->repository->update($uid, $type, $achievementId, $goal,true);
    }
}