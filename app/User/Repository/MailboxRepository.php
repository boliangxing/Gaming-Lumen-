<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Repository;

use App\Common\Counters\TipsCounter;
use Cai\Foundation\Repository;

class MailboxRepository extends Repository
{
    protected $_connection = 'user';

    protected $_table = 'user_mailboxes';

    const STATUS_DELETED = -1;
    const STATUS_UNREAD = 0;
    const STATUS_READ = 1;

    const CATEGORY_ALL = 0;
    const CATEGORY_UNREAD = 1;
    const CATEGORY_READ = 2;

    /**
     * @param $fromUid
     * @param $toUid
     * @param $fromType
     * @param $messageType
     * @param $content
     * @return int
     */
    public function sendMessage($fromUid, $toUid, $fromType, $messageType, $content)
    {
        $messageRepository = new MessageRepository();

        $now = date('Y-m-d H:i:s');
        $messageId = $messageRepository->addMessage($fromType, $messageType, $content, $now);

        $counter = new TipsCounter();
        $counter->setKey($toUid)->incrBy(TipsCounter::UNREAD_MESSAGES);

        // @todo bi-direction ?
        return $this->table()->insertGetId([
            'uid' => $toUid,
            'from_uid' => $fromUid,
            'from_type' => $fromType,
            'status' => $messageId,
            'updated_at' => $now,
        ]);
    }

    public function sendSystemMessage($fromUid, $fromType, $messageType, $content, $chunkedUsers)
    {
        $messageRepository = new MessageRepository();

        $now = date('Y-m-d H:i:s');
        $messageId = $messageRepository->addMessage($fromType, $messageType, $content, $now);
    }

    /**
     * 获取通知列表
     *
     * @param $uid
     * @param $category
     * @param int $cursor
     * @param int $size
     * @return array
     */
    public function getMessages($uid, $category, $cursor = 0, $size = self::PAGE_SIZE)
    {
        $query = $this->table()->where('uid', $uid);

        if ($category == self::CATEGORY_UNREAD) {
            $query = $query->where('status', self::STATUS_UNREAD);
        } else if ($category == self::CATEGORY_READ) {
            $query = $query->where('status', self::STATUS_READ);
        } else {
            $query = $query->where('status', '<>', self::STATUS_DELETED);
        }

        if ($cursor) {
            $query->where('id', '<', $cursor);
        }

        $pagination = $query->orderByDesc('id')->simplePaginate($size);

        $incomingLogs = $pagination->items();

        if (count($incomingLogs) == 0) {
            return $this->emptyPagination();
        }

        $messageIds = array_map(function ($message) {
            return $message->message_id;
        }, $incomingLogs);

        $messageRepository = new MessageRepository();

        $messages = $messageRepository->getMessages($messageIds);

        if (count($messages) == 0) {
            return $this->emptyPagination();
        }

        $normalizedMessages = [];
        foreach ($incomingLogs as $incomingLog) {
            if (!isset($messages[$incomingLog->message_id])) {
                continue;
            }

            $message = $messages[$incomingLog->message_id];

            $normalizedMessages[] = [
                'id' => $incomingLog->id,
                'from_uid' => $incomingLog->from_uid,
                'from_type' => $incomingLog->from_type,
                'message_type' => $message->message_type,
                'content' => $message->content,
                'created_at' => $message->created_at,
                'updated_at' => $incomingLog->updated_at,
            ];
        }

        return [
            'items' => $normalizedMessages,
            'cursor' => $this->paginate($pagination),
        ];
    }

    protected function getFromText()
    {

    }
}