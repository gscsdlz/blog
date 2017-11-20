<?php
/**
 * Created by PhpStorm.
 * User: 南宫悟
 * Date: 2017/11/16
 * Time: 18:05
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Model\BlogModel;
use App\Model\TypeModel;
use Illuminate\Http\Request;

class BlogController extends Controller
{

    private static $type = null;
    public function __construct()
    {
        if(is_null(self::$type))
            self::$type = new TypeModel(true);
    }

    public function edit(Request $request)
    {

        return view('admin.blog_edit', [
            'types' => self::$type->types,
            'menu' => 'blog@edit',
        ]);
    }

    public function add(Request $request)
    {
        $title = $request->get('title');
        $type = $request->get('type');
        $text = $request->get('text');
        $mdtext = $request->get('mdtext');

        $blog = new BlogModel();
        $bid = $blog->save([
           'title' => $title,
           'text' => $text,
           'mdtext' => $mdtext,
           'time' => time(),
            'type' => $type,
        ]);

        return response()->json(['status' => true, 'bid' => $bid]);
    }

    public function list_blog(Request $request, $page = 1)
    {
        $pl = ($page - 1) * 20;
        $pr = $pl + 20;
        $blogs = self::$type->pages($pl, $pr);
        return view('admin.blog_list', [
            'menu' => 'blog@list',
            'lists' => $blogs,
        ]);
    }
}