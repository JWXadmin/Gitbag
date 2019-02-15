<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 13:17
 */
/*某个用户发布的文章列表*/
namespace app\index\controller;

class Collection extends Base{

    public function index(){
        $this->assign('action','collection');
        return $this->fetch();
    }
}