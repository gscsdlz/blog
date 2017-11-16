<?php
/**
 * Created by PhpStorm.
 * User: 南宫悟
 * Date: 2017/11/16
 * Time: 18:48
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function image(Request $request)
    {
        $file = $request->file('editormd-image-file');
        if ($file->isValid()) {
            if(strpos($file->getMimeType(), 'image') !== false) {
                $path = $file->store('public/images/blog/');
                if($path !== false) {
                    $arr = [
                        'success' => 1,
                        'message' => "上传成功！",
                        'url' => URL('images/blog/'.basename($path))
                    ];
                } else {
                    $arr = [
                        'success' => 0,
                        'message' => "文件保存失败！",
                    ];
                }
            } else {
                $arr = [
                    'success' => 0,
                    'message' => "文件类型错误",
                ];
            }
        } else {
           $arr = [
               'success' => 0,
                'message' =>  "上传失败",
           ];
        }
        return response()->json($arr);
    }
}