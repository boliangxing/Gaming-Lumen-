<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Repository;

use Cai\Foundation\Repository;

class UserLoginHistoryRepository extends Repository
{
    protected $_connection = 'user';

    protected $_table = 'user_login_histories';

    const STATUS_FAILED = 0;
    const STATUS_SUCCESS = 1;

    const CLIENT_TYPE_WEB = 1;
    const CLIENT_TYPE_MOBILE = 2;
    const CLIENT_TYPE_WECHAT = 3;

    /**
     * 登录记录
     *
     * @param $uid
     * @param $ip
     * @param $clientType
     * @param $ua
     * @param $cityId
     * @param $address
     * @param $status
     * @return bool
     */
    public function addHistory($uid, $ip, $clientType, $ua, $cityId, $address, $status)
    {
        $now = date('Y-m-d H:i:s');

        return $this->table()->insert([
            'uid' => $uid,
            'ip' => $ip,
            'client_type' => $clientType,
            'ua' => $ua,
            'city_id' => $cityId,
            'address' => $address,
            'status' => $status,
            'created_at' => $now,
        ]);
    }

    /**
     * 获取用户登录历史记录
     *
     * @param $uid
     * @param int $cursor
     * @param int $size
     * @param int $status
     * @return array
     */
    public function getLoginHistory($uid, $cursor = 0, $size = self::PAGE_SIZE, $status = self::STATUS_SUCCESS)
    {
        $query = $this->table()->select(['id','ip','address','created_at'])->where('uid', $uid);

        if ($cursor) {
            $query = $query->where('id', '<', $cursor);
        }

        $pagination = $query->where('status', $status)->simplePaginate($size);

        $loginHistories = $pagination->items();

        if (count($loginHistories) == 0) {
            return $this->emptyPagination();
        }

        return [
            'items' => $loginHistories,
            'cursor' => $this->paginate($pagination),
        ];
    }
}