<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Middleware;

use Cai\Exceptions\AuthException;
use Cai\Exceptions\BadHttpRequestException;
use Illuminate\Http\Request;

class AdminJWTMiddleware
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(&$request, \Closure $next)
    {
        $version = $this->getVersion($request);

        //@todo: 验证API版本

        if (\Auth::guard('admin')->user() === null) {
            throw new AuthException('请登录');
        }

        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    protected function getVersion(Request $request)
    {
        $accept = $request->header('Accept');

        $pattern = '/application\/vnd\.shoucai\.v([\d\.]+)\+json/';
        if (preg_match($pattern, $accept, $matches)) {
            $version = $matches[1];

            return $version;
        }

        throw new BadHttpRequestException('请求信息错误');
    }


}