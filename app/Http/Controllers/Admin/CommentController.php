<?php
/**
 * Created by PhpStorm.
 * User: 南宫悟
 * Date: 2017/11/27
 * Time: 20:33
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends  Controller
{
    public function list_all(Request $request)
    {
        return view('admin.comments');
    }
}