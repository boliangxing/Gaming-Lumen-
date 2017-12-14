<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Listeners;

use App\User\Events\UserProfileChanged;
use App\User\Repository\UserHistoryRepository;

class UserProfileChangedNotification
{
    public function handle(UserProfileChanged $event)
    {
        $repository = new UserHistoryRepository();
        $repository->addHistoryLog($event->getUid(), $event->getEventType(), $event->getChangedProperties());
    }
}