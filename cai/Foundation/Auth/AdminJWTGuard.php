<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Foundation\Auth;

use App\User\Repository\UserLoginHistoryRepository;
use Cai\Exceptions\AuthException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use InvalidArgumentException;
use UnexpectedValueException;

class AdminJWTGuard implements Guard
{
    use GuardHelpers;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create a new authentication guard.
     *
     * @param  \Illuminate\Contracts\Auth\UserProvider $provider
     * @param  \Illuminate\Http\Request $request
     */
    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->provider = $provider;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (!is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        $uid = $this->getJwtUid();

        if ($uid !== null) {
            $user = $this->provider->retrieveById($uid);
        }

        return $this->user = $user;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return false;
    }

    /**
     * Set the current request instance.
     *
     * @param  \Illuminate\Http\Request $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * 手机号 + 密码登录
     *
     * @param array $credentials
     * @return string
     */
    public function attempt(array $credentials = [])
    {
        $user = $this->provider->retrieveByCredentials([
            'admin_name' => $credentials['username'],
        ]);

        if ($user === null) {
            throw new AuthException('用户名或密码不正确');
        }

        if ($user->status == 0) {
            throw new AuthException('账户被禁用');
        }

        if (! $this->provider->validateCredentials($user, ['password' => $credentials['password']])) {

            throw new AuthException('用户名或密码不正确');
        }

        // @todo: 设备指纹，登录记录

        // 生成JWT token
        $token = JWT::encode(['uid' => $user->getAuthIdentifier()], env('JWT_API'));

        return $token;
    }

    /**
     * 退出登录
     */
    public function logout()
    {

    }

    /**
     * 用户历史登录记录
     *
     * @param $uid
     * @param $clientType
     * @param $status
     */
    protected function addUserLoginHistory($uid, $clientType, $status)
    {
        $logHistoryRepository = new UserLoginHistoryRepository();

        $ip = $this->request->ip();
        $region = app('ip')->getRegion($ip);

        $logHistoryRepository->addHistory($uid, $ip, $clientType, $this->request->userAgent(),
            $region['city_id'], $region['region'], $status);
    }

    /**
     * 获取token中的用户信息
     *
     * @return mixed
     */
    protected function getJwtUid()
    {
        $token = $this->request->bearerToken();

        if ($token === null) {
            return null;
        }

        try {
            $decoded = JWT::decode($token, env('JWT_API'), ['HS256']);
        } catch (SignatureInvalidException | BeforeValidException $e) {
            throw new AuthException('登录状态非法');
        } catch (ExpiredException $e) {
            // @todo: 过期处理
            throw new AuthException('登录状态过期，请重新登录');
        } catch (UnexpectedValueException $e) {
            throw new AuthException('登录状态非法');
        } catch (InvalidArgumentException $e) {
            throw new AuthException('登录配置信息错误');
        }

        return $decoded->uid;
    }
}