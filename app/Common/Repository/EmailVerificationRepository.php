<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Common\Repository;

use Cai\Foundation\Repository;
use Carbon\Carbon;

class EmailVerificationRepository extends Repository
{
    protected $_connection = 'user';

    protected $_table = 'email_verifications';

    const STATUS_UNVERIFIED = 1;  // 未验证

    const STATUS_VERIFIED = 2;    // 已验证成功

    const SCENE_REGISTER = 1;  // 注册

    const SCENE_BIND = 2;      // 绑定

    /**
     * 添加邮箱验证码
     *
     * @param $email
     * @param $code
     * @param $usage
     * @param $expiredAt
     * @return bool
     */
    public function addEmailVerification($email, $code, $usage, $expiredAt)
    {
        $now = date('Y-m-d H:i:s');

        return $this->table()->insert([
            'email' => $email,
            'code' => $code,
            'usage' => $usage,
            'status' => self::STATUS_UNVERIFIED,
            'expired_at' => $expiredAt,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    /**
     * 获取最后发送的验证码记录
     *
     * @param $email
     * @param $usage
     * @param int $delta
     * @return int
     */
    public function getLastVerificationId($email, $usage, $delta = 5)
    {
        $lastTime = date('Y-m-d H:i:s', time() - $delta * 60);

        return $this->table()
            ->where('email', $email)
            ->where('created_at', '>=', $lastTime)
            ->where('usage', $usage)
            ->where('status', self::STATUS_UNVERIFIED)
            ->orderByDesc('created_at')
            ->first(['id', 'code']);
    }

    /**
     * 延长过期时间
     *
     * @param $id
     * @param $expiredAt
     * @return int
     */
    public function touchExpiredAt($id, $expiredAt)
    {
        return $this->table()->where('id', $id)
            ->where('status', self::STATUS_UNVERIFIED)
            ->update([
                'expired_at' => $expiredAt,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    /**
     * 获取验证码
     *
     * @param $email
     * @param $since
     * @param $usage
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getEmailVerification($email, $since, $usage)
    {
        return $this->table()
            ->where('email', $email)
            ->where('created_at', '>=', $since)
            ->where('usage', $usage)
            ->where('status', self::STATUS_UNVERIFIED)
            ->orderByDesc('created_at')
            ->first(['id', 'code']);
    }

    /**
     * 标记为已验证
     *
     * @param $id
     * @return int
     */
    public function markAsVerified($id)
    {
        return $this->table()->where('id', $id)
            ->where('status', self::STATUS_UNVERIFIED)
            ->update([
                'status' => self::STATUS_VERIFIED,
                'updated_at' => date('Y-m-d H:i:s'),
            ]) === 1;
    }

    /**
     * 每个邮箱发送邮件数限定
     *
     * @param $email
     * @return bool
     */
    public function isAllowedToSendMail($email)
    {
        // 1天内最多10封?
        $start = Carbon::yesterday()->format('Y-m-d H:i:s');

        return $this->table()->where('email', $email)
            ->where('created_at', '>=', $start)
            ->count() < 10;
    }
}