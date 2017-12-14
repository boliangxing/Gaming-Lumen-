<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Mails;

use Illuminate\Mail\Mailable;

class RegisterMailable extends Mailable
{
    protected $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.users.register', $this->params)
            ->subject('注册验证码');
    }
}