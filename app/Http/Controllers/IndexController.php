<?php
/**
 * Created by PhpStorm.
 * User: 南宫悟
 * Date: 2017/11/16
 * Time: 10:29
 */

namespace App\Http\Controllers;


use App\Model\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
    public function test(Request $request)
    {
        $redis = Redis::connection('user');

    }

    public function index(Request $request)
    {
        $user  = new UserModel();
        return view('index', [
            'user' => $user
        ]);
    }
}