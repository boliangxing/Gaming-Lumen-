<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Events;

class UserProfileChanged
{
    const USER_REGISTERED = 1;
    const NICKNAME_CHANGED = 2;
    const EMAIL_CHANGED = 3;
    const MOBILE_CHANGED = 4;
    const AVATAR_CHANGED = 5;
    const BIO_CHANGED = 6;

    const REGISTER_TYPE_EMAIL = 1;
    const REGISTER_TYPE_MOBILE = 2;
    const REGISTER_TYPE_QQ = 3;
    const REGISTER_TYPE_WEIBO = 4;
    const REGISTER_TYPE_WEIXIN = 5;

    protected $eventType;

    protected $uid;

    protected $changedProperties;

    public function __construct($eventType, $uid, $changedProperties)
    {
        $this->eventType = $eventType;
        $this->uid = $uid;
        $this->changedProperties = $changedProperties;
    }

    /**
     * @return mixed
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return mixed
     */
    public function getChangedProperties()
    {
        return $this->changedProperties;
    }

}