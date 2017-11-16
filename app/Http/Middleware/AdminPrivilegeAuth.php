<?php
/**
 * Created by PhpStorm.
 * User: 南宫悟
 * Date: 2017/11/16
 * Time: 14:08
 * 检查是否有权进入后台
 */

namespace App\Http\Middleware;


use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Session;

class AdminPrivilegeAuth
{
    public function handle(Request $request, Closure $next, $graud = null)
    {
        if(Session::has('username')) {
            return $next($request);
        } else {
            return redirect('admin/');
        }
    }
}