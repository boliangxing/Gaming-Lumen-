<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Foundation;

use App\Common\Repository\EmailVerificationRepository;
use Cai\Exceptions\DailyEmailExceedException;
use Cai\Exceptions\VerificationException;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * 验证码生成、管理
 *
 * Class VerificationCode
 * @package Cai\Foundation
 */
class VerificationCode
{
    const CAPTCHA_CODE_PREFIX = 'v_cap_';

    /**
     * 生成邮件验证码
     *
     * @param $email
     * @param $usage
     * @return mixed|string
     */
    public static function generateEmailCode($email, $usage)
    {
        $usage = self::getEmailUsage($usage);

        $repository = self::getRepository();

        $expiredAt = self::getEmailExpiredAt($usage);

        if (($verificationLog = $repository->getLastVerificationId($email, $usage)) !== null) {
            $code = $verificationLog->code;

            if ($repository->touchExpiredAt($verificationLog->id, $expiredAt)) {
                // @todo: 重新记录次数

                return $code;
            }

            \Log::err('更新验证码过期时间失败');

            return false;
        } else {
            $code = self::generateRandomNum();

            if (!$repository->isAllowedToSendMail($email)) {
                throw new DailyEmailExceedException('该邮箱每日邮件发送已达上限');
            }

            $repository->addEmailVerification($email, $code, $usage, $expiredAt);

            return $code;
        }
    }

    /**
     * 验证邮件验证码
     *
     * @param $email
     * @param $code
     * @param $usage
     * @return bool
     */
    public static function verifyEmailCode($email, $code, $usage)
    {
        $usage = self::getEmailUsage($usage);

        // @todo: 错误统计

        $repository = self::getRepository();

        $since = self::getEmailGeneratedSince($usage);

        $log = $repository->getEmailVerification($email, $since, $usage);

        if ($log === null) {
            throw new VerificationException('验证码不存在或已过期，请重新获取');
        }

        if ($log->code == $code) {
            // 把该验证码设置为已验证
            if ($repository->markAsVerified($log->id)) {
                return true;
            }

            throw new VerificationException('验证码不存在或已过期，请重新获取');
        }

        return false;
    }

    public static function generateCaptchaCode()
    {

    }

    protected static function getEmailGeneratedSince($usage)
    {
        $minutes = self::getEmailExpireMinutes($usage);

        return Carbon::now()->subMinutes($minutes)->format('Y-m-d H:i:s');
    }

    protected static function getEmailUsage($usage)
    {
        switch ($usage) {
            case 'register':
                return EmailVerificationRepository::SCENE_REGISTER;
            case 'bind':
                return EmailVerificationRepository::SCENE_BIND;
        }
    }

    protected static function getEmailExpiredAt($usage)
    {
        $minutes = self::getEmailExpireMinutes($usage);

        return Carbon::now()->addMinutes($minutes)->format('Y-m-d H:i:s');
    }

    protected static function getEmailExpireMinutes($usage)
    {
        switch ($usage) {
            default:
                $minutes = 10;
                break;
        }

        return $minutes;
    }

    protected static function verifyCode($key, $code)
    {
        if (($realCode = \Cache::get($key)) === null) {
            return false;
        }

        return $realCode == $code;
    }

    protected static function generateRandomNum($num = 6)
    {
        return mt_rand(1000, 9999);
    }

    protected static function generateRandomAlnum($num = 6)
    {
        return strtolower(Str::random($num));
    }

    /**
     * @return EmailVerificationRepository
     */
    protected static function getRepository()
    {
        return new EmailVerificationRepository;
    }
}