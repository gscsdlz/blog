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
use Illuminate\Http\Request;

class IndexController extends Controller
{
    private $navbar = null;

    public function __construct()
    {
        $type = new TypeModel(false);
        $this->navbar = $type->get_navbar();
    }

    public function index(Request $request)
    {
        return view('index', [
            'menu' => 'index',
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
            'blog' => $blog,
            'navbar' => $this->navbar,
            'neededitorMD' => true,
        ]);
    }

    public function blog_list(Request $request, $page =  1)
    {
        $blog = new BlogModel();
        if($page < 1)
            $page = 1;
        $arr = $blog->list_all($page);

        return view('blog_list', [
            'arr' => $arr[0],
            'total' => $arr[1],
            'page' => $page,
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
            'page' => $page,
            'type' => $types,
            'menu' => 'type@'.$types,
            'navbar' => $this->navbar,
        ]);
    }

    public function type_list(Request $request)
    {
        $t = new TypeModel();
        return view('type_list', [
            'arr' => $t->types,
            'menu' => 'typeAll',
            'navbar' => $this->navbar,
        ]);
    }
}