<?php
/**
 * Copyright (c) 2017.  收菜网
 * Date: 2017/10/19
 * Time: 17:19
 */

namespace App\User\Listeners;

use App\User\Repository\UserGuessRepository;
use App\User\Events\UserMissionCompleted;

class GuessSubscriber
{

    protected $repository;

    const GUESS_TYPE_CAI = 1;
    const GUESS_TYPE_CARD = 2;

    public function __construct(UserGuessRepository $userGuessRepository)
    {
        $this->repository = $userGuessRepository;

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
            'App\User\Listeners\GuessSubscriber@onTest'
        );
    }

    public function handleGuess($isWin, $costType, $cost, $income)
    {
        $uid = $this->getUserId();
        $this->repository->checkDate($uid);
        if (self::GUESS_TYPE_CAI == $costType){
            $data = $this->repository->get($uid);
            $guessTimes = $data->guess_times + 1;
            $guessWinTimes = $data->guess_win_times;
            if ($isWin) $guessWinTimes++;
            $costAll = $data->cai_cost_all + $cost;
            $incomeDay = $data->cai_income_day + $income;
            $incomeWeek = $data->cai_income_week + $income;
            $incomeMonth = $data->cai_income_month + $income;
            $incomeAll = $data->cai_income_all + $income;
            $this->repository->updateWithCai($uid, $guessTimes, $guessWinTimes,
                $costAll, $incomeDay, $incomeWeek, $incomeMonth, $incomeAll);
        }
        elseif (self::GUESS_TYPE_CARD == $costType){
            $data = $this->repository->get($uid);
            $guessTimes = $data->guess_times + 1;
            $guessWinTimes = $data->guess_win_times;
            if ($isWin) $guessWinTimes++;
            $costAll = $data->card_cost_all + $cost;
            $incomeDay = $data->card_income_day + $income;
            $incomeWeek = $data->card_income_week + $income;
            $incomeMonth = $data->card_income_month + $income;
            $incomeAll = $data->card_income_all + $income;
            $this->repository->updateWithCard($uid, $guessTimes, $guessWinTimes,
                $costAll, $incomeDay, $incomeWeek, $incomeMonth, $incomeAll);
        }
    }

    protected function getUserId()
    {
        return 1;
    }
}