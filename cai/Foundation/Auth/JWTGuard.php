<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Foundation\Auth;

use App\User\Exceptions\UserForbiddenLoginException;
use App\User\Repository\UserClientRepository;
use App\User\Repository\UserCredentialRepository;
use App\User\Repository\UserLoginHistoryRepository;
use App\User\Repository\UserRepository;
use Cai\Exceptions\AuthException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use InvalidArgumentException;
use UnexpectedValueException;

class JWTGuard implements Guard
{
    use GuardHelpers;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $cid;

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

        $userInfo = null;

        $uid = $this->getJwtUid();

        if (!empty($uid)) {
            $userRepository = new UserRepository();
            $userInfo = $userRepository->getById($uid);

            if ($userInfo === null) {
                throw new AuthException('用户信息错误');
            }

            if ($userInfo->banned_expiration) {
                if ($userInfo->banned_expiration >= SC_START_TIME) {
                    // @todo: 时间点
                    throw new UserForbiddenLoginException(sprintf('该账号已禁用，请在%s后尝试登录', '30分'));
                } else {
                    // 已过时间，清空禁用信息
                    $userRepository->clearBannedInfo($userInfo->id);
                }
            }

//            $user = $this->provider->retrieveByCredentials(
//                ['id' => $uid]
//            );
        }

        return $this->user = $userInfo;
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
            'mobile' => $credentials['mobile'],
        ]);

        if ($user === null) {
            throw new AuthException('用户名或密码不正确');
        }

        $clientType = $this->request->input('client_type', UserLoginHistoryRepository::CLIENT_TYPE_WEB);

        // 用户是否被禁止登录
        $userRepository = new UserRepository();
        $userInfo = $userRepository->getById($user->id);
        if ($userInfo === null) {
            throw new AuthException('用户名或密码不正确');
        }

        if ($userInfo->banned_expiration) {
            if ($userInfo->banned_expiration > SC_START_TIME) {
                // @todo: 时间点
                throw new UserForbiddenLoginException(sprintf('该账号已禁用，请在%s后尝试登录', '30分'));
            } else {
                // 已过时间，清空禁用信息
                $userRepository->clearBannedInfo($user->id);
            }
        }

        $credentialRepository = new UserCredentialRepository();

        if (! $this->provider->validateCredentials($user, ['password' => $credentials['password']])) {
            $this->addUserLoginHistory($user->id,
                $clientType,
                UserLoginHistoryRepository::STATUS_FAILED);

            $credentialRepository->incrementErrorLogTime($user->id);

            throw new AuthException('用户名或密码不正确');
        }

        $this->addUserLoginHistory($user->id,
            $clientType,
            UserLoginHistoryRepository::STATUS_SUCCESS);

        if ($user->error_tries > 0) {
            // @todo 登录成功，错误处理
            $credentialRepository->clearLoginError($user->id);
        }

        // @todo: 设备指纹，登录记录

        // 生成JWT token， 过时间等
        $token = JWTHelper::encode(['jti' => $user->getAuthIdentifier()], $this->request);

        return $token;
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        $userClientRepository = new UserClientRepository();

        $userClientRepository->clearLoginClient($this->user->id, $this->cid,
            $this->request->input('client_type', UserLoginHistoryRepository::CLIENT_TYPE_WEB));
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
            $decoded = JWTHelper::decode($token, $this->request);
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

        $this->cid = $decoded->cid;

        return $decoded->jti;
    }
}