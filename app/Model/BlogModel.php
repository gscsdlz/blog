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
 * 分类 types 集合类型 键名为 类型名 值为使用该类型的文章ID
 *
 */

namespace App\Model;


use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class BlogModel
{
    private $dbname = 'blog';  //对应 config/database.php redis的配置
    private $redis = null; //redis连接

    private $blogID = null;
    private $title = null;
    private $text = null;
    private $textPath = null;
    private $mdtext = null;
    private $mdtextPath = null;
    private $type = null;
    private $time = null;
    private $view = null;
    private $updateTime = null;
    private $updateCount = null;

    public function __construct($blogID = null)
    {
        $this->redis = Redis::connection($this->dbname);
        if(!is_null($blogID)) {
            $arr = $this->redis->hgetall('blogID:'.$blogID);
            if(count($arr) == 8) {
                $this->blogID = $blogID;
                $this->title = $arr['title'];
                $this->textPath = $arr['textPath'];
                $this->text = Storage::get('public/blog/html_file/'.$this->textPath);
                $this->mdtextPath = $arr['mdtextPath'];
                $this->mdtext = Storage::get('public/blog/md_file/'.$this->mdtextPath);
                $this->view = $arr['view'];
                $this->time = $arr['time'];
                $this->type = $arr['type'];
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
     * 调用保存的时候，会自动将HTML文件存储为本地文本文件，并保留路径
     * @return int
     */
    public function save($arr = null)
    {
        if(!is_null($arr)) {
            $this->title = $arr['title'];
            $this->text = $arr['text'];
            $this->mdtext = $arr['mdtext'];
            $this->time = $arr['time'];
            $this->type = $arr['type'];

            $this->view = isset($arr['view']) ? $arr['view'] : 0;
            $this->updateTime = isset($arr['updateTime']) ? $arr['updateTime'] : 0;
            $this->updateCount = isset($arr['updateCount']) ? $arr['updateCount'] : 0;
        }


        $this->mdtextPath = time().rand(1, 1000).".md";
        Storage::put('public/blog/md_file/'.$this->mdtextPath, $this->mdtext);


        $this->textPath =  time().rand(1, 1000).".html";
        Storage::put('public/blog/html_file/'.$this->textPath, $this->text);

        $pk = $this->redis->get('primaryKey');
        if(is_null($pk)) {  //第一次使用
            $pk = 0;
        }
        $pk = $pk + 1;
        $this->redis->hmset('BlogID:'.$pk, array(
            'title' => $this->title,
            'textPath' =>  $this->textPath,
            'mdtextPath' => $this->mdtextPath,
            'type' => $this->type,
            'time' => $this->time,
            'view' => $this->view,
            'updateTime' => $this->updateTime,
            'updateCount' =>$this->updateCount
        ));
        $this->redis->incr('primaryKey');  //更新伪外键
        $this->redis->sadd('Types:'.$this->type, $pk, '-1'); //redis中不允许空集合 加入-1区分

        return $pk;
    }
}