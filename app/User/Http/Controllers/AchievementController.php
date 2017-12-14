<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Http\Controllers;

use Cai\Foundation\Controller;
use App\User\Repository\UserAchievementRepository;

class AchievementController extends Controller
{
    protected $config;
    protected $achievementList;
    protected $repository;

    public function __construct(UserAchievementRepository $userAchievementRepository)
    {
        $this->config = require __DIR__.'/../../config/achievement.php';

        $this->achievementList = $this->config['achievementList'];
        $this->repository = $userAchievementRepository;

        parent::__construct();
    }

    public function getList()
    {
        $list = $this->achievementList;
        var_dump($list);
        $uid = $this->getUserId();
        //var_dump($list);
        foreach ($list as $type => &$achievements){
            foreach ($achievements as $achievementId => &$achievement)
            {
                $achievement['des'] = sprintf($achievement['des'], $achievement['goal']);
                $achievementData = $this->repository->get($uid, $type, $achievementId);
                if ($achievementData){
                    $achievement['now'] = $achievementData->now;
                    $achievement['is_complete'] = $achievementData->is_complete;
                    $achievement['is_Taken'] = $achievementData->is_taken;
                }
                else{
                    $achievement['now'] = 0;
                    $achievement['is_complete'] = 0;
                    $achievement['is_taken'] = 0;
                }
            }
        }

        $data = [
            'types' => $this->config['achievementTypes'],
            'list' => $list,
        ];

        return $this->success($data);
    }

    public function getReward()
    {
        $type = $this->request->input('type');
        $achievementId = $this->request->input('id');
        $uid = $this->getUserId();
        if(!$this->repository->isComplete($uid, $type, $achievementId))
            return $this->fail('领取奖励失败, 成就还未完成');

        $achievementData = $this->repository->get($uid, $type, $achievementId);
        if ($achievementData->is_taken)
            return $this->fail('不能重复领取奖励');

        $achievementConfig = $this->achievementList[$type][$achievementId];
        $reward = $achievementConfig['reward'];
        //TODO:发奖励

        $this->repository->updateRewardState($uid, $type, $achievementId);
        return $this->success([], '领取奖励成功');
    }

}