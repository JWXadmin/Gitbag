<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 12:44
 */
/*联系我们*/
namespace app\index\controller;

class Contact extends Base{


    public function index(){
        $this->assign('action','联系我');
        return $this->fetch();
    }
}