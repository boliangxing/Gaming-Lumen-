<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Repository;

use Cai\Foundation\Repository;

class MessageRepository extends Repository
{
    protected $_connection = 'user';

    protected $_table = 'messages';

    const STATUS_NORMAL = 1;
    const STATUS_DELETED = -1;

    const FROM_SYSTEM = 1;
    const FROM_USER = 2;
    const FROM_SUBSCRIPTION = 3;

    /**
     * 添加消息
     *
     * @param $fromType
     * @param $messageType
     * @param $content
     * @param $createdAt
     * @return int
     */
    public function addMessage($fromType, $messageType, $content, $createdAt)
    {
        return $this->table()->insertGetId([
            'message_type' => $messageType,
            'from_type' => $fromType,
            'content' => $content,
            'status' => self::STATUS_NORMAL,
            'created_at' => $createdAt,
        ]);
    }


    public function getMessages($messages)
    {
        return $this->table()->whereIn('id', $messages)
            ->where('status', self::STATUS_NORMAL)
            ->get()->mapWithKeys(function ($message) {
                return [$message->id => $message];
            })->toArray();
    }
}