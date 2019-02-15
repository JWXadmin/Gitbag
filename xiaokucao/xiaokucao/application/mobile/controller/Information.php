<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5
 * Time: 14:52
 */
namespace app\mobile\controller;

use think\Controller;


class Information extends Controller{

    public function index(){

        return $this->fetch();
    }

    public function information_3(){
        return $this->fetch();
    }

    public function information_2(){
        return $this->fetch();
    }
}