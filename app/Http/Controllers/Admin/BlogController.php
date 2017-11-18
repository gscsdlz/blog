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
    public function edit(Request $request)
    {
        $type = new TypeModel();

        return view('admin.blog_edit', [
            'types' => $type->types,
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
}