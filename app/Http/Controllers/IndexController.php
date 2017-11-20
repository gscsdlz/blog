<?php
/**
 * Created by PhpStorm.
 * User: 南宫悟
 * Date: 2017/11/16
 * Time: 10:29
 */

namespace App\Http\Controllers;


use App\Model\BlogModel;
use App\Model\TypeModel;
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
        $type = new TypeModel(false);
        $navbar = $type->get_navbar();
        return view('index', [
            'user' => $user,
            'navbar' => $navbar,
        ]);
    }

    public function blog(Request $request, $bid)
    {
        $blog = new BlogModel($bid);
        $user  = new UserModel();
        $type = new TypeModel(false);
        $navbar = $type->get_navbar();
        return view('blog', [
            'user' => $user,
            'blog' => $blog,
            'navbar' => $navbar,
        ]);
    }
}