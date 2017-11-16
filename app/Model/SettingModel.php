<?php
/**
 * Created by PhpStorm.
 * User: 南宫悟
 * Date: 2017/11/16
 * Time: 15:10
 */

namespace App\Model;

use Illuminate\Support\Facades\Redis;

class SettingModel
{
    private $dbname = 'setting';  //对应 config/database.php redis的配置
    private $redis = null; //redis连接
    public $adminEmail = null;

    public function __construct()
    {
        $this->redis = Redis::connection($this->dbname);

        //首先使用redis中的邮箱 如果没有则调用配置文件中的邮箱。
        $this->adminEmail = $this->redis->get('adminEmail');
        if(is_null($this->adminEmail)) {
            $this->adminEmail = config('blog.adminEmail');
        }
    }


}