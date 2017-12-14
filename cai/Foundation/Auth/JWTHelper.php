<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Foundation\Auth;

use App\User\Exceptions\UserClientIllegalException;
use App\User\Repository\UserClientRepository;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Rhumsaa\Uuid\Uuid;

class JWTHelper
{
    /**
     * 7 天有效时间
     */
    const EXPIRED_AFTER = 7;

    public static $leeway = 0;

    const GUARD_API = 'JWT_API';
    const GUARD_BACKEND = 'JWT_ADMIN';

    const CLIENT_WEB = 1;
    const CLIENT_MOBILE = 2;

    const ALGORITHM = 'HS256';

    public static function encode($params, Request $request, $source = self::CLIENT_WEB, $guard = self::GUARD_API)
    {
        $now = time();

        $expiredAt = $now + self::EXPIRED_AFTER * 86400;

        $clientId = self::generateUuid();

        $extraParams = [
            'iat' => $now,
            'nbf' => $now,
            'exp' => $expiredAt,
            'cid' => $clientId,
        ];

        self::recordClientId($params['jti'], $clientId, $source, $request, $expiredAt);

        return JWT::encode($params + $extraParams, env($guard), self::ALGORITHM);
    }

    public static function decode($token, Request $request, $source = self::CLIENT_WEB, $guard = self::GUARD_API)
    {
        $decoded = JWT::decode($token, env($guard), [self::ALGORITHM]);

        $repository = new UserClientRepository();
        $clientLog = $repository->getClientLog($decoded->jti, $decoded->cid, $source);

        if (strtotime($clientLog->expired_at) != $decoded->exp) {
            throw new UserClientIllegalException('登录信息错误');
        }

        return $decoded;

        // refresh token
    }

    protected static function recordClientId($uid, $clientId, $source, Request $request, $expiredAt)
    {
        $repository = new UserClientRepository();

        $repository->addClientHistory($uid, $clientId, $source, (bool) $request->input('remember_me', 1),
            $request->input('trusted_client', 1),
            $request->ip(), $request->userAgent(), date('Y-m-d H:i:s', $expiredAt));
    }

    protected static function generateUuid()
    {
        return Uuid::uuid4()->toString();
    }

}