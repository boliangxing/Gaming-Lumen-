<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Repository;

use Cai\Foundation\Repository;
use Carbon\Carbon;

class UserGuessRepository extends Repository
{
    protected $_table = 'user_guess';

    protected $_connection = 'user';

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
            'last_update' => date('Y-m-d H:i:s'),
        ]);
    }

    public function checkDate($uid){
        $data = $this->get($uid);
        $lastUpdateTime = Carbon::createFromFormat("Y-m-d H:i:s", $data->last_update);

        if (!$lastUpdateTime->isToday()){
            //更新日
            $this->table()->where([
                ['uid', $uid]
            ])->update(['cai_income_day' => 0, 'card_income_day' => 0]);
        }

        if (!$lastUpdateTime->isSameAs('Y-W')){
            //更新周
            $this->table()->where([
                ['uid', $uid]
            ])->update(['cai_income_week' => 0, 'card_income_week' => 0]);
        }

        if (!$lastUpdateTime->isSameAs('Y-m')){
            //更新月
            $this->table()->where([
                ['uid', $uid]
            ])->update(['cai_income_month' => 0, 'card_income_month' => 0]);
        }

    }

    public function updateWithCai($uid, $guessTimes, $guessWinTimes,
                              $caiCostAll, $caiIncomeDay, $caiIncomeWeek, $caiIncomeMonth, $caiIncomeAll)
    {
        return $this->table()->where([
            ['uid', $uid]
        ])->update([
            'guess_times' => $guessTimes,
            'guess_win_times' => $guessWinTimes,
            'cai_cost_all' => $caiCostAll,
            'cai_income_day' => $caiIncomeDay,
            'cai_income_week' => $caiIncomeWeek,
            'cai_income_month' => $caiIncomeMonth,
            'cai_income_all' => $caiIncomeAll,
            'last_update' => date('Y-m-d H:i:s'),
        ]);
    }

    public function updateWithCard($uid, $guessTimes, $guessWinTimes,
                                   $cardCostAll, $cardIncomeDay, $cardIncomeWeek, $cardIncomeMonth, $cardIncomeAll)
    {
        return $this->table()->where([
            ['uid', $uid]
        ])->update([
            'guess_times' => $guessTimes,
            'guess_win_times' => $guessWinTimes,
            'card_cost_all' => $cardCostAll,
            'card_income_day' => $cardIncomeDay,
            'card_income_week' => $cardIncomeWeek,
            'card_income_month' => $cardIncomeMonth,
            'card_income_all' => $cardIncomeAll,
            'last_update' => date('Y-m-d H:i:s'),
        ]);
    }

    private function update($uid, $guessTimes, $guessWinTimes,
                           $caiCostAll, $caiIncomeDay, $caiIncomeWeek, $caiIncomeMonth, $caiIncomeAll,
                           $cardCostAll, $cardIncomeDay, $cardIncomeWeek, $cardIncomeMonth, $cardIncomeAll)
    {
        return $this->table()->where([
            ['uid', $uid]
        ])->update([
            'guess_times' => $guessTimes,
            'guess_win_times' => $guessWinTimes,
            'cai_cost_all' => $caiCostAll,
            'cai_income_day' => $caiIncomeDay,
            'cai_income_week' => $caiIncomeWeek,
            'cai_income_month' => $caiIncomeMonth,
            'cai_income_all' => $caiIncomeAll,
            'card_cost_all' => $cardCostAll,
            'card_income_day' => $cardIncomeDay,
            'card_income_week' => $cardIncomeWeek,
            'card_income_month' => $cardIncomeMonth,
            'card_income_all' => $cardIncomeAll,
            'last_update' => date('Y-m-d H:i:s'),
        ]);
    }


}