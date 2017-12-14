<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Admin\Repository;

use Cai\Foundation\Repository;

class AdminLogRepository extends Repository
{
    protected $_connection = 'system';

    protected $_table = 'admin_logs';

    /**
     * 添加访问请求日志
     *
     * @param $uid
     * @param $path
     * @param $ip
     * @param $ua
     * @param $params
     * @return bool
     */
    public function addLog($uid, $path, $ip, $ua, $params)
    {
        return $this->table()->insert([
            'uid' => $uid,
            'uri' => ltrim($path, '/'),
            'ip' => $ip,
            'ua' => $ua,
            'params' => json_encode($params),
            'created_at' => SC_START_TIME,
        ]);
    }
}