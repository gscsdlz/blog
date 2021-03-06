<?php
/**
 * Created by PhpStorm.
 * User: 南宫悟
 * Date: 2017/11/18
 * Time: 11:09
 */

namespace App\Model;


use Illuminate\Support\Facades\Redis;

class TypeModel
{
    private $dbname = 'blog';  //对应 config/database.php redis的配置
    private $redis = null; //redis连接

    private $types = null;

    public function __construct($load = true)
    {
        $this->redis = Redis::connection($this->dbname);
        $this->types = [];

        //是否启用初始化
        if($load == true) {
            $res = $this->redis->keys('Types*');
            foreach ($res as $t) {
                //Types:XXXX
                $arr = explode(":", $t);
                $nums = $this->redis->llen('Types:' . $arr[1]);
                $this->types[] = [$arr[1], $nums];
            }
        }
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function update($oldName, $newName)
    {
        return $this->redis->rename('Types:'.$oldName, 'Types:'.$newName);
    }

    public function get_navbar()
    {
        return $this->redis->zrange('TypeNavbar', 0, -1, 'WITHSCORES');
    }

    public function add($name)
    {
        if($this->redis->llen('Types:'.$name) == 0)
            $this->redis->lpush('Types:'.$name, -1);
        //redis中不予许空集合 这里加入-1 区分
    }

    public function del($name)
    {
        $len = $this->redis->llen('Types:'.$name);
        if($len == 1 && $this->redis->lindex('Types:'.$name, 0) == "-1") {
            $this->redis->del('Types:' . $name);
            return true;
        } else {
            return false;
        }

    }

    public function set_navbar($lists)
    {
        //清空有序集合
        $this->redis->del('TypeNavbar');

        $this->redis->pipeline(function($pipe) use ($lists){
            foreach ($lists as $t) {
                $pipe->zadd('TypeNavbar', $t[1], $t[0]);
            }
        });

    }

    public function pages()
    {
        $len = $this->redis->llen('BlogIDIndex');

        $lists = $this->redis->lrange('BlogIDIndex', 0, $len - 1);
        $arr = [];
        foreach ($lists as $l) {
            $arr[$l] = $this->redis->hgetall('BlogID:'.$l);
        }
        return $arr;
    }
}