<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Repository;

use Cai\Foundation\Repository;

class UserRepository extends Repository
{
    protected $_table = 'users';

    protected $_connection = 'user';

    const STATUS_BANNED = -2;
    const STATUS_DELETED = -1;
    const STATUS_NORMAL = 1;
    const STATUS_NOT_ALLOWED_COMMENT = 2;

    const UN_BAN = 0;           // 解除禁用
    const BAN_30_MINUTES = 1;   // 禁用半小时
    const BAN_1_HOUR = 2;       // 禁用1小时
    const BAN_1_DAY = 3;        // 禁用1天
    const BAN_3_DAYS = 4;       // 禁用3天
    const BAN_FOREVER = 5;      // 永久禁用

    /**
     * 注册新用户
     *
     * @param $nickname
     * @param $avatar
     * @param int $gender
     * @param string $bio
     * @return int
     */
    public function register($nickname, $avatar = '', $gender = 0, $bio = '')
    {
        return $this->table()->insertGetId([
            'nickname' => $nickname,
            'avatar' => $avatar,
            'gender' => $gender,
            'bio' => $bio,
            'registered_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * 修改昵称
     *
     * @param $uid
     * @param $nickname
     * @return int
     */
    public function updateNickname($uid, $nickname)
    {
        return $this->table()->where('id', $uid)
            ->update(['nickname' => $nickname]);
    }

    /**
     * 修改头像
     *
     * @param $uid
     * @param $avatar
     * @return int
     */
    public function updateAvatar($uid, $avatar)
    {
        return $this->table()->where('id', $uid)
            ->update(['avatar' => $avatar]);
    }

    /**
     * 修改个性签名
     *
     * @param $uid
     * @param $bio
     * @return int
     */
    public function updateBio($uid, $bio)
    {
        return $this->table()->where('id', $uid)
            ->update([
                'bio' => $bio
            ]);
    }

    public function clearBannedInfo($uid)
    {
        return $this->table()->where('id', $uid)
            ->update([
                'status' => self::STATUS_NORMAL,
                'banned_level' => self::UN_BAN,
                'banned_expiration' => $this->raw('NULL'),
            ]);
    }
}