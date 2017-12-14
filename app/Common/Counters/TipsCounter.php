<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Common\Counters;

use Cai\Foundation\Counter;

class TipsCounter extends Counter
{
    protected $prefixGroup = 'tips';

    // 未读消息数
    const UNREAD_MESSAGES = 'um';

    // 未领取的任务奖励数
    const UNREWARDED_TASKS = 'ut';

    // 未领取的成就奖励数
    const UNREWARDED_ACHIEVEMENTS = 'uc';

    /**
     * 设置unique key
     *
     * @param $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->prefixGroup .= '_' . $key;

        return $this;
    }

    public function hasTips($page)
    {

    }
}