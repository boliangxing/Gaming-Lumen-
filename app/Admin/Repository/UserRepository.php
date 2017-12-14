<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace App\Admin\Repository;

use Cai\Exceptions\UserNotFoundException;
use Cai\Foundation\Repository;
use Cai\Foundation\Utils;
use Cai\Foundation\Validator;
use Carbon\Carbon;

class UserRepository extends Repository
{
    protected $_connection = 'user';

    protected $_table = 'users';

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
     * 用户列表
     *
     * @param $params
     * @return array
     */
    public function search($params)
    {
        $query = $this->table('u');

        if (!empty($params['username'])) {
            if (strpos($params['username'], '@') !== false) {
                $query = $query->where('uc.email', $params['username']);
            } elseif (Validator::isMobile($params['username'])) {
                $query = $query->where('uc.mobile', $params['username']);
            } else {
                $query = $query->where('u.nickname', 'like', '%'.$params['username'].'%');
            }
        }

        if (!empty($params['register_started'])) {
            $query = $query->where('u.registered_at', '>=', $params['register_started']);

            if (!empty($params['register_ended'])) {
                $query = $query->where('u.registered_at', '<=', $params['register_ended']);
            }
        }

        $size = $params['size'] ?: self::PAGE_SIZE;

        $paginator = $query->join('user_credentials AS uc', 'uc.id', '=', 'u.id')
            ->orderByDesc('u.id')->paginate($size);

        if ($paginator->isEmpty()) {
            return [
                'total' => 0,
                'items' => [],
            ];
        }

        $normalizedUsers = [];

        foreach ($paginator->items() as $user) {
            $normalizedUsers[] = [
                'id' => $user->id,
                'avatar' => avatar($user->avatar),
                'nickname' => $user->nickname,
                'email' => Utils::maskEmail($user->email),
                'mobile' => Utils::maskMobile($user->mobile),
                'cai' => $user->cai - $user->consumed_cai,
                'status' => $user->status,
            ];
        }

        return [
            'total' => $paginator->total(),
            'items' => $normalizedUsers,
        ];
    }

    /**
     * 获取用户详情
     *
     * @param $id
     * @return array
     */
    public function getById($id)
    {
        $user = $this->table('u')->join('user_credentials AS uc', 'uc.id', '=', 'u.id')
            ->where('u.id', $id)->first();

        if ($user === null) {
            throw new UserNotFoundException('用户不存在');
        }

        $normalizedUser =  [
            'id' => $user->id,
            'avatar' => avatar($user->avatar),
            'nickname' => $user->nickname,
            'email' => Utils::maskEmail($user->email),
            'mobile' => Utils::maskMobile($user->mobile),
            'cai' => $user->cai - $user->consumed_cai,
            'status' => $user->status,
        ];

        return $normalizedUser;
    }

    /**
     * 禁用或解禁用户
     *
     * @param $uid
     * @param $option
     * @return bool
     */
    public function ban($uid, $option)
    {
        $user = $this->table()->where('id', $uid)->first();

        if ($user === null || $user->status == self::STATUS_DELETED) {
            throw new UserNotFoundException;
        }

        switch ($option) {
            case self::BAN_30_MINUTES:
                $minutes = 30;
                break;
            case self::BAN_1_HOUR:
                $minutes = 60;
                break;
            case self::BAN_1_DAY:
                $minutes = 24 * 60;
                break;
            case self::BAN_3_DAYS:
                $minutes = 3 * 24 * 60;
                break;
            case self::BAN_FOREVER:
                $minutes = 24 * 60 * 365 * 20;
                break;
            default:
                $minutes = 0;
                break;
        }

        $revokedAt = Carbon::today()->addMinutes($minutes)->format('Y-m-d H:i:s');

        if ($minutes > 0) {
            // 禁用
            $this->table()->where('id', $uid)->update([
                'status' => self::STATUS_BANNED,
                'banned_expiration' => $revokedAt,
                'banned_level' => $option,
            ]);
        } else {
            $this->table()->where('id', $uid)->update([
                'status' => self::STATUS_NORMAL,
                'banned_expiration' => $this->raw('NULL'),
                'banned_level' => self::UN_BAN,
            ]);
        }

        return true;
    }
}