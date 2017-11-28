<?php
/**
 * Created by PhpStorm.
 * User: 南宫悟
 * Date: 2017/11/16
 * Time: 10:29
 */

namespace App\Http\Controllers;


use App\Model\BlogModel;
use App\Model\CommentModel;
use App\Model\TypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
        $lists = CommentModel::get_comments($bid);
        if(!is_null($blog->blogID) && Session::get('viewID', -1) != $blog->blogID){
            BlogModel::incView($bid);
            Session::put('viewID', $blog->blogID);
        }
        return view('blog', [
            'blog' => $blog,
            'navbar' => $this->navbar,
            'neededitorMD' => true,
            'comments' => $lists,
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
            'arr' => $arr[0],
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

    public function comments(Request $request)
    {
        $bid = Session::get('viewID');
        $email = $request->get('email', null);
        $text = $request->get('text', null);

        if(!is_null($text) || (strlen($text) != 0 && strlen($text) < 1000)) {
            CommentModel::insert($bid, $email, htmlspecialchars($text), time());
            return response()->json(['status' => true]);
        }
    }
}