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

    public function edit(Request $request, $bid = null)
    {

        if(is_null($bid)) {
            return view('admin.blog_edit', [
                'types' => self::$type->types,
                'menu' => 'blog@edit',
            ]);
        } else {
            $blog = new BlogModel($bid);
            return view('admin.blog_edit', [
                'types' => self::$type->types,
                'menu' => 'blog@edit',
                'blog' => $blog,
                'bid' => (int)$bid,
            ]);
        }
    }

    public function add(Request $request)
    {
        $title = $request->get('title');
        $type = $request->get('type');
        $mdtext = $request->get('mdtext');
        $bid = $request->get('blogID', null);

        if(is_null($bid)) {
            $blog = new BlogModel();
            $bid = $blog->save([
                'title' => $title,
                'mdtext' => $mdtext,
                'time' => time(),
                'type' => $type,
            ]);
        } else {
            $blog = new BlogModel($bid);
            $blog->title = $title;
            $blog->oldType = $blog->type;
            $blog->type = $type;
            $blog->mdtext = $mdtext;

            $blog->save();

        }

        return response()->json(['status' => true, 'blogID' => $bid]);
    }

    public function list_blog(Request $request)
    {
        $blogs = self::$type->pages();
        return view('admin.blog_list', [
            'menu' => 'blog@list',
            'lists' => $blogs,
        ]);
    }

    public function del(Request $request)
    {
        $bid = $request->get('bid');
        BlogModel::del($bid);
        return response()->json(['status'=>true]);
    }
}