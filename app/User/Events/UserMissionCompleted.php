<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Events;

class UserMissionCompleted
{
    const TASK = 1;
    const DAILY_TASK = 2;
    const ACHIEVEMENT = 3;

    protected $type;
    protected $uid;
    protected $missionData;

    public function __construct($type, $uid, $missionData)
    {
        $this->type = $type;
        $this->uid = $uid;
        $this->missionData = $missionData;
    }

    public function getEventType()
    {
        return $this->type;
    }

    public function getUserId()
    {
        return $this->uid;
    }

    public function getMissionData()
    {
        return $this->missionData;
    }

}