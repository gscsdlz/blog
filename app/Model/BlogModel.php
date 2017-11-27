<?php
/**
 * Created by PhpStorm.
 * User: 南宫悟
 * Date: 2017/11/17
 * Time: 16:09
 * 博客文章模型装在一个散列中
 * 散列的键名为blog:blogID
 * 每个散列的子键包括如下内容
 * 文章名.title | 内容（HTML）路径.text | 内容（MD）路径.mdtext | 分类.type | 创建时间.time | 浏览次数.view | 修改时间.updateTime | 修改次数.updateCount
 * 维护一个字符串作用类似于主键 blogPrimaryKey
 *
 * 分类 types 列表类型 键名为 类型名 值为使用该类型的文章ID
 *
 */

namespace App\Model;


use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class BlogModel
{
    private $dbname = 'blog';  //对应 config/database.php redis的配置
    private $redis = null; //redis连接

    private $blogID = null;
    private $title = null;
    private $mdtext = null;
    private $mdtextPath = null;
    private $type = null;
    private $time = null;
    private $view = null;
    private $updateTime = null;
    private $updateCount = null;
    private $visible = null;

    private $oldType = null; //保留原始type类型 可能为空 但主要给修改文章时使用

    public function __construct($blogID = null)
    {
        $this->redis = Redis::connection($this->dbname);
        if(!is_null($blogID)) {
            $arr = $this->redis->hgetall('BlogID:'.$blogID);

            if(count($arr) != 0) {
                $this->blogID = $blogID;
                $this->title = $arr['title'];
                $this->mdtextPath = $arr['mdtextPath'];
                $this->view = $arr['view'];
                $this->time = $arr['time'];
                $this->type = $arr['type'];
                $this->visible = $arr['visible'];
                $this->updateTime = $arr['updateTime'];
                $this->updateCount = $arr['updateCount'];
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

    /**
     * @param array
     *
     * 调用保存的时候，会自动将MarkDown文件存储为本地文本文件，并保留路径
     * @return int
     */
    public function save($arr = null)
    {
        if(!is_null($arr)) {
            $this->title = $arr['title'];
            $this->mdtext = $arr['mdtext'];
            $this->time = $arr['time'];
            $this->type = $arr['type'];
            $this->visible = 1;

            $this->view = isset($arr['view']) ? $arr['view'] : 0;
            $this->updateTime = isset($arr['updateTime']) ? $arr['updateTime'] : $this->time;
            $this->updateCount = isset($arr['updateCount']) ? $arr['updateCount'] : 0;
        }

        if(isset($this->mdtextPath)) {
            $this->redis->hset('BlogID:'.$this->blogID, 'title', $this->title);
            $this->redis->hset('BlogID:'.$this->blogID, 'type', $this->type);
            $this->redis->hset('BlogID:'.$this->blogID, 'updateTime', time());
            $this->redis->hincrby('BlogID:'.$this->blogID, 'updateCount', 1);
            if(!is_null($this->oldType) && $this->oldType != $this->type) {
                $this->redis->lrem('Types:'.$this->oldType, 0, $this->blogID);
                $this->redis->rpush('Types:'.$this->type, $this->blogID);
            }
            Storage::put('public/blog/md_file/'.$this->mdtextPath, $this->mdtext);

            return $this->blogID;
        } else {
            $this->mdtextPath = time() . rand(1, 1000) . ".md";
            Storage::put('public/blog/md_file/' . $this->mdtextPath, $this->mdtext);

            $pk = $this->redis->get('primaryKey');
            if(is_null($pk)) {  //第一次使用
                $pk = 0;
            }
            $pk = $pk + 1;
            $this->redis->hmset('BlogID:'.$pk, array(
                'title' => $this->title,
                'mdtextPath' => $this->mdtextPath,
                'type' => $this->type,
                'time' => $this->time,
                'view' => $this->view,
                'updateTime' => $this->updateTime,
                'updateCount' =>$this->updateCount,
                'visible' => 1,
            ));
            $this->redis->incr('primaryKey');  //更新伪外键
            if($this->redis->llen('Types:'.$this->type) == 0)
                $this->redis->lpush('Types:'.$this->type, $pk); //redis中不允许空列表 加入-1区分
            $this->redis->lpush('Types:'.$this->type, $pk);
            $this->redis->rpush('BlogIDIndex', $pk);

            return $pk;
        }
    }

    public function list_all($page)
    {
        $len = $this->redis->llen('BlogIDIndex');
        $total = (int)(($len - 1) / 20) + 1;
        if($page > $total)
            $page = $total;
        $pl = ($page - 1) * 20;
        $pr = $pl + 20;

        $lists = $this->redis->lrange('BlogIDIndex', $pl, $pr);
        $arr = [];
        foreach ($lists as $l) {
            $arr[$l] = $this->redis->hgetall('BlogID:'.$l);
        }
        return [$arr, $total];
    }

    public function list_withTypes($type, $page)
    {
        $pms = config('blog.blogPage');
        $len = $this->redis->llen('Types:'.$type);
        if($len == 1)
            return [[], 0];

        $total = (int)(($len - 1) / $pms) + 1;
        if($page > $total)
            $page = $total;
        $pl = ($page - 1) * $pms;
        $pr = $pl + $pms + 1;  //多取一位 避开之前的-1

        $lists = $this->redis->lrange('Types:'.$type, $pl, $pr);
        $arr = [];
        foreach ($lists as $l) {
            if($l != -1)
                $arr[$l] = $this->redis->hgetall('BlogID:'.$l);
        }
        return [$arr, $total];
    }

    public static function del($bid) {
        $redis = Redis::connection('blog');
        $path = $redis->hget('BlogID:'.$bid, 'mdtextPath');
        $type = $redis->hget('BlogID:'.$bid, 'type');
        Storage::delete('public/blog/md_file/'.$path);
        $redis->srem('Types:'.$type, $bid);
        $redis->lrem('BlogIDIndex', '0', $bid);
        $redis->del('BlogID:'.$bid);
    }

    public static function incView($bid)
    {
        $redis = Redis::connection('blog');
        $redis->hincrby('BlogID:'.$bid, 'view', 1);
    }
}