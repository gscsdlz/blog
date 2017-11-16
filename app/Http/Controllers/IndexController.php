<?php
/**
 * Created by PhpStorm.
 * User: 南宫悟
 * Date: 2017/11/16
 * Time: 10:29
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
    public function test(Request $request)
    {
        $redis = Redis::connection('user');
        $redis->set('key', '1234');
        dd($redis->get('key'));
    }
}