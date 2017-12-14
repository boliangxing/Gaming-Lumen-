<?php
/**
 * Copyright (c) 2017.  收菜网
 */

namespace Cai\Middleware;

use App\Admin\Repository\AdminLogRepository;
use Illuminate\Http\Request;

class AdminRequestLogMiddleware
{
    /**
     * Run the request filter.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(&$request, \Closure $next)
    {
        $this->addRequestLog($request);

        return $next($request);
    }

    protected function addRequestLog(Request $request)
    {
        $uid = \Auth::guard('admin')->user()->id ?? 0;

        $logHistory = new AdminLogRepository();

        $params = $request->input();

        unset($params['password']);

        $logHistory->addLog($uid, $request->getPathInfo(), $request->ip(), $request->userAgent(), $params);
    }
}