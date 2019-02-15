<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 11:19
 */
/*好像没有用*/
namespace app\index\controller;

class User extends Base{

    public function index(){

        $this->assign('action','个人中心');
        return $this->fetch();

    }
}