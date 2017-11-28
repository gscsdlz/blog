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

use Illuminate\Support\Facades\Redis;

class CommentModel
{
    private static $redisHandle = null;


    private function __construct()
    {
        self::$redisHandle = Redis::connection('blog');
    }

    public static function getInstance()
    {
        if(is_null(self::$redisHandle))
            new CommentModel();
        return self::$redisHandle;
    }

    public static function insert($blogID, $email, $text, $time)
    {
        $redis = self::getInstance();
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
        $redis = self::getInstance();
        $redis->lrem('Comments:'.$bid, 0,  $redis->lindex('Comments:'.$bid, $index));
    }

    public static function get_index()
    {
        $redis = self::getInstance();
        $keys = $redis->keys('Comments:*');
        foreach ($keys as &$k) {
            $k = substr($k, 9);
            $arr = $redis->hget('BlogID:' . $k, 'title');
            $k = [$k, $arr];
        }
        unset($k);
        return  $keys;
    }

    public static function get_comments($bid)
    {
        $redis = self::getInstance();
        $arr = $redis->lrange('Comments:'.$bid, 0, -1);
        $comments = [];
        $k = 0;
        foreach ($arr as $str) {
            $comments[$k] = json_decode($str, true);
            $comments[$k]['time'] = date('Y-m-d H:i', $comments[$k]['time']);
            $k++;
        }
        return $comments;
    }

    public static function  add($blogID, $index, $text)
    {
        $redis = self::getInstance();
        $str = json_encode([
            'email' => "管理员",
            'text' => $text,
            'time' => time(),
            'isAdmin' => 1,
        ]);
        $redis->linsert('Comments:'.$blogID, 'AFTER', $redis->lindex('Comments:'.$blogID, $index), $str);
    }

    public static function get_info($blogID, $index)
    {
        $redis = self::getInstance();
        $res = $redis->lindex('Comments:'.$blogID, $index);
        return json_decode($res, true);
    }

}