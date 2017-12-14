<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\User\Repository;

use App\User\Exceptions\UserForbiddenLoginException;
use Cai\Foundation\Repository;

class UserCredentialRepository extends Repository
{
    protected $_connection = 'user';

    protected $_table = 'user_credentials';

    /**
     * 添加用户登录凭证
     *
     * @param $uid
     * @param $email
     * @param $countryCode
     * @param $mobile
     * @param $password
     * @return bool
     */
    public function addUserCredentials($uid, $email, $countryCode, $mobile, $password)
    {
        return $this->table()->insert([
            'id' => $uid,
            'email' => $email,
            'country_code' => $countryCode,
            'mobile' => $mobile,
            'password' => app('hash')->make($password),
        ]);
    }

    /**
     * 邮箱或手机号是否已注册
     *
     * @param $field
     * @param $value
     * @return bool
     */
    public function hasEmailOrMobileRegistered($field, $value)
    {
        return $this->table()->where($field, $value)
                ->count() === 1;
    }

    /**
     * 修改密码
     *
     * @param $uid
     * @param $password
     * @return int
     */
    public function updatePassword($uid, $password)
    {
        $hash = app('hash');

        return $this->table()->where('id', $uid)
            ->update(['password' => $hash->make($password)]);
    }

    /**
     * 修改密码时验证旧密码是否正确
     *
     * @param $uid
     * @param $oldPassword
     * @return bool
     */
    public function checkPassword($uid, $oldPassword)
    {
        // @todo: 安全机制

        $user = $this->getById($uid);
        $hash = app('hash');

        if ($hash->check($oldPassword, $user->password) === false) {
            return false;
        }

        return true;
    }

    /**
     * 修改手机号
     *
     * @param $uid
     * @param $mobile
     * @return int
     */
    public function updateMobile($uid, $mobile)
    {
        return $this->table()->where('id', $uid)
            ->update([
                'mobile' => $mobile,
            ]);
    }

    /**
     * 修改邮箱
     *
     * @param $uid
     * @param $email
     * @return int
     */
    public function updateEmail($uid, $email)
    {
        return $this->table()->where('id', $uid)
            ->update([
                'email' => $email,
            ]);
    }

    /**
     * 登录错误次数，是否禁止
     *
     * @param $uid
     * @return int
     */
    public function incrementErrorLogTime($uid)
    {
        $credentialLog = $this->getById($uid);

        $now = time();
        // 判断上次登录错误时间, 与当前时间是否超过30分钟，
        $delta = $now - strtotime($credentialLog->tried_at);
        if ($credentialLog->error_tries >= 4 && $delta < 1800) {
            // 账号禁用
            $this->incErrLog($uid);

            throw new UserForbiddenLoginException('该账号错误登录次数已达上限');
        }

        if ($delta > 86400) {
            return $this->table()->where('id', $uid)
                ->update([
                    'error_tries' => 1,
                    'tried_at' => date('Y-m-d H:i:s', $now),
                ]);
        }

        return $this->incErrLog($uid);
    }

    /**
     * 重置登录错误信息
     *
     * @param $uid
     * @return int
     */
    public function clearLoginError($uid)
    {
        return $this->table()->where('id', $uid)
            ->update([
                'error_tries' => 0,
                'tried_at' => $this->raw('null'),
            ]);
    }

    protected function incErrLog($uid)
    {
        return $this->table()->where('id', $uid)
            ->update([
                'error_tries' => $this->raw('error_tries + 1'),
                'tried_at' => date('Y-m-d H:i:s'),
            ]);
    }
}