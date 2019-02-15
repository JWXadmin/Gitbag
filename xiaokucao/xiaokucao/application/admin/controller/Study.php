<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/20
 * Time: 14:46
 */
namespace app\admin\controller;

class Study extends Base{


    public function index(){

        return $this->fetch();
    }


}