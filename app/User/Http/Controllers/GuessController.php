<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Http\Controllers;

use Cai\Foundation\Controller;
use App\User\Repository\UserGuessRepository;

class GuessController extends Controller
{

    protected $repository;

    public function __construct(UserGuessRepository $userGuessRepository)
    {

        $this->repository = $userGuessRepository;
        parent::__construct();
    }

    public function getInfo()
    {
        $uid = $this->getUserId();
        $data = $this->repository->get($uid);

        $winRate = 0;
        if (0 != $data->guess_times) $winRate = round($data->guess_win_times/$data->guess_times, 2);

        $result = [
            'guess_times' => $data->guess_times,
            'win_rate' => $winRate,
            'cai_cost_all' => $data->cai_cost_all,
            'cai_income_day' => $data->cai_income_day,
            'cai_income_week' => $data->cai_income_week,
            'cai_income_month' => $data->cai_income_month,
            'cai_income_all' => $data->cai_income_all,
            'card_cost_all' => $data->card_cost_all,
            'card_income_day' => $data->card_income_day,
            'card_income_week' => $data->card_income_week,
            'card_income_month' => $data->card_income_month,
            'card_income_all' => $data->card_income_all,
        ];

        return $this->data($result);
    }

}