<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Shop\Events;

class ShopProfileChanged
{


    protected $uid;

    public function __construct($uid)
    {

        $this->uid = $uid;

    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }



}