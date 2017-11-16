<?php
/**
 * Created by PhpStorm.
 * User: 南宫悟
 * Date: 2017/11/16
 * Time: 18:05
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function edit(Request $request)
    {
        return view('admin.blog_edit');
    }
}