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
                $nums = $this->redis->scard('Types:' . $arr[1]);
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
        $this->redis->exists('Types:'.$name);
        $this->redis->sadd('Types:'.$name, '-1');
        //redis中不予许空集合 这里加入-1 区分
    }

    public function del($name)
    {
        $this->redis->del('Types:'.$name);
    }
}