<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Http\Controllers;

use Cai\Foundation\Controller;
use App\User\Repository\UserCheckInRepository;
use Cache;
use Carbon\Carbon;
use Predis\Command\SetCardinality;

class CheckInController extends Controller
{
    protected $repository;
    protected $taskList;
    public function __construct(UserCheckInRepository $userCheckInRepository)
    {
        $config = require __DIR__.'/../../config/checkIn.php';
        $this->taskList = $config['taskList'];
        $this->repository = $userCheckInRepository;

        parent::__construct();

    }

    public function checkIn()
    {
        $uid = $this->getUserId();
        $key = $this->getCheckInKey($uid);
        $data = Cache::get($key);
        if ($data && $data == date('Ymd')) {
            return $this->fail('您今天已经签到过了');
        }
        else {
            if($this->handleCheckIn($uid)){
                Cache::put($key, date('Ymd'), 24*60);
                return $this->success('签到成功');
            }
            else{
                return $this->fail('签到失败，请重新尝试');
            }
        }
    }

    public function test()
    {
        $this->handleCheckIn(1);
    }

    protected function handleCheckIn($uid)
    {
        $data = $this->repository->get($uid);
        //连续签到处理
        $continuousCount = $data->continuous_count;
        $lastCheckInTime = Carbon::createFromFormat("Y-m-d H:i:s", $data->last_check_in);
        if ($lastCheckInTime->isYesterday())
            $continuousCount++;
        else
            $continuousCount = 1;
        //签到任务完成情况处理
        $thisMonthCount = $data->this_month_count + 1;
        $level = $data->level;
        if (array_key_exists($level, $this->taskList)) {
            $taskConfig = $this->taskList[$level];
            if ($thisMonthCount >= $taskConfig['goal'])
            {
                $level++;
            }
        }

        return $this->repository->update($uid, $thisMonthCount, $continuousCount, $level);
    }

    public function getCheckInData()
    {
        $uid = $this->getUserId();
        $data = $this->repository->get($uid);
        $result = [
            'this_month_count' => $data->this_month_count,
            'continuous_count' => $data->continuous_count,
            'rank' => $data->rank,
            'last_check_in' => $data->last_check_in,
            'reward_state' => $data->reward_state,
            'level' => $data->level,
        ];

        return $this->data($result);
    }

    public function getReward()
    {
        $uid = $this->getUserId();
        $level = $this->request->input('level');
        $data = $this->repository->get($uid);
        if ($level >= $data->level)
            return $this->fail('您未达到领取奖励的条件，请继续努力');

        if (array_key_exists($level, $this->taskList)) {
            $rewardState = json_decode($data->reward_state, true);
            if ($rewardState[$level]) {
                return $this->fail('您已经领取过奖励了');
            }
            $taskConfig = $this->taskList[$level];
            $reward = $taskConfig['reward'];
            //TODO:发奖励

            $this->repository->updateRewardState($uid, $level);
            return $this->success('奖励领取成功');
        }

    }

    protected function getCheckInKey($uid)
    {
        return sprintf('user_check_in_date_%d', $uid);
    }

}