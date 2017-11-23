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
    private $navbar = null;
    private $user = null;
    public function __construct()
    {
        $type = new TypeModel(false);
        $this->navbar = $type->get_navbar();

        $this->user  = new UserModel();
    }

    public function index(Request $request)
    {
        return view('index', [
            'menu' => 'index',
            'user' =>  $this->user,
            'navbar' => $this->navbar,
        ]);
    }

    public function blog(Request $request, $bid)
    {
        $blog = new BlogModel($bid);
        if(!is_null($blog->blogID)){
            BlogModel::incView($bid);
        }
        return view('blog', [
            'user' =>  $this->user,
            'blog' => $blog,
            'navbar' => $this->navbar,
            'neededitorMD' => true,
        ]);
    }

    public function blog_list(Request $request, $page =  1)
    {
        $blog = new BlogModel();
        $arr = $blog->list_all($page);
        return view('blog_list', [
            'arr' => $arr,
            'user' =>  $this->user,
            'menu' => 'blog@all',
            'navbar' => $this->navbar,
        ]);
    }

    public function blog_types(Request $request, $types, $page = 1)
    {
        $blog = new BlogModel();
        $arr = $blog->list_withTypes($types, $page);
        return view('blog_list', [
            'arr' => $arr,
            'user' =>  $this->user,
            'menu' => 'type@'.$types,
            'navbar' => $this->navbar,
        ]);
    }
}