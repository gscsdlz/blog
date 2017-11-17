<?php
/**
 * Created by PhpStorm.
 * User: 南宫悟
 * Date: 2017/11/16
 * Time: 10:42
 * 用户模型，关联一个用户，与redis进行交互
 */

namespace App\Model;

use Illuminate\Support\Facades\Redis;

class UserModel
{
    private $dbname = 'user';  //对应 config/database.php redis的配置
    private $redis = null; //redis连接
    private $variable = array(); //绑定用户属性 key->value

    public function __construct()
    {
        $this->redis = Redis::connection($this->dbname);
        $this->variable = $this->redis->hgetall('user');
    }

    public function __set($name, $value)
    {
        $this->variable[$name] = $value;
    }

    public function __get($name)
    {
        return isset($this->variable[$name]) ? $this->variable[$name] : null;
    }


}