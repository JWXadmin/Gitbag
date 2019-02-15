<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 13:20
 */
/*某个用户的粉丝*/
namespace app\index\controller;
class Focus extends Base{

    public function index(){

        $this->assign('action','focus');
        return $this->fetch();
    }
}