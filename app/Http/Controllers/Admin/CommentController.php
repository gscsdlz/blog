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
use App\Model\CommentModel;
use Illuminate\Support\Facades\Mail;
use PhpParser\Comment;

class CommentController extends  Controller
{
    public function list_all(Request $request)
    {
        $keys = CommentModel::get_index();
        return view('admin.comments', [
            'menu' => 'comments',
            'keys' => $keys,
        ]);
    }

    public function get(Request $request)
    {
        $bid = $request->get('bid');
        $lists = CommentModel::get_comments($bid);
        return response()->json([
            'status' => true,
            'lists' => $lists,
        ]);
    }

    public function del(Request $request)
    {
        $bid = $request->get('bid');
        $index = $request->get('id');
        CommentModel::del($bid, $index);
        return response()->json(['status' => true]);
    }

    public function insert(Request $request)
    {
        $bid = $request->get('bid');
        $index = $request->get('id');
        $text = $request->get('text');
        $email = $request->get('email');

        CommentModel::add($bid, $index, $text);

        if($email == 1) {
            $res = CommentModel::get_info($bid, $index);
            Mail::send('mail.reply', ['comment' => $res['text'], 'reply' => $text], function($message) use ($res) {
                $to = $res['email'];
                $message->to($to)->subject("留言回复");
            });
            if(empty(Mail::failures()))
                return response()->json(['status' => true]);
            else
                return response()->json(['status' => false, 'info' => 'SError']); //System Error
        }

        return response()->json(['status' => true]);
    }
}

