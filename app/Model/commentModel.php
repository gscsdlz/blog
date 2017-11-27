<?php
/**
 * Created by PhpStorm.
 * User: 南宫悟
 * Date: 2017/11/27
 * Time: 19:28
 *
 * 用于管理留言 针对博客进行存储，包括留言的时间，邮箱，内容，博客ID 是否是管理员 简单的使用json存储 放置于列表中
 * Comments:XX
 */

namespace App\Model;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class commentModel
{
    private $blodID = null;
    private $redis = null;
    private $comments = null;
    public function __construct($blogID = null)
    {
        $this->redis = Redis::connection('blog');
        if(!is_null($blogID)) {
            $this->blodID = $blogID;
            $arr = $this->redis->lindex('Comments:'.$blogID, 0, -1);
            foreach ($arr as $str) {
                $this->comments[] = json_decode($str, true);
            }
        }
    }

    public static function insert($blogID, $email, $text, $time)
    {
        $redis = Redis::connection('blog');
        $str = json_encode([
            'email' => $email,
            'text' => $text,
            'time' => $time,
            'isAdmin' => 0,
        ]);
        $redis->rpush('Comments:'.$blogID, $str);
    }

    public static function del($bid, $index)
    {
        $redis = Redis::connection('blog');
        $redis->lrem('Comments:'.$bid, 0,  $redis->lindex('Comments:'.$bid, $index));
    }
}