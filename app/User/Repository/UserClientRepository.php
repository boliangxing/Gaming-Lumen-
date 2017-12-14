<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Repository;

use App\User\Exceptions\UserClientIllegalException;
use Cai\Foundation\Repository;

class UserClientRepository extends Repository
{
    protected $_connection = 'user';

    protected $_table = 'user_clients';

    const STATUS_NORMAL = 0;
    const STATUS_CLEARED = 1;

    /**
     * 添加用户客户端记录
     *
     * @param $uid
     * @param $clientId
     * @param $source
     * @param $rememberMe
     * @param $trustedClient
     * @param $ip
     * @param $ua
     * @param $expiredAt
     * @return bool
     */
    public function addClientHistory($uid, $clientId, $source, $rememberMe, $trustedClient, $ip, $ua, $expiredAt)
    {
        return $this->table()->insert([
            'uid' => $uid,
            'client_id' => $clientId,
            'source' => $source,
            'ip' => $ip,
            'ua' => $ua,
            'remember_me' => $rememberMe,
            'trusted_client' => $trustedClient,
            'expired_at' => $expiredAt,
            'cleared' => self::STATUS_NORMAL,
            'created_at' => SC_START_TIME,
            'updated_at' => SC_START_TIME,
        ]);
    }

    /**
     * 获取用户客户端记录
     *
     * @param $uid
     * @param $clientId
     * @param $source
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getClientLog($uid, $clientId, $source)
    {
        $log = $this->table()->where('uid', $uid)
            ->where('client_id', $clientId)
            ->where('source', $source)
            ->where('cleared', self::STATUS_NORMAL)
            ->first();

        if ($log === null) {
            throw new UserClientIllegalException('登录信息错误');
        }

        return $log;
    }

    /**
     * 清除客户端登录记录
     *
     * @param $uid
     * @param $clientId
     * @param $source
     * @return int
     */
    public function clearLoginClient($uid, $clientId, $source)
    {
        return $this->table()->where('uid', $uid)
            ->where('client_id', $clientId)
            ->where('source', $source)
            ->where('cleared', self::STATUS_NORMAL)
            ->update([
                'cleared' => self::STATUS_CLEARED,
                'updated_at' => SC_START_TIME,
            ]);
    }
}