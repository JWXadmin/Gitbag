<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5
 * Time: 14:07
 */
namespace app\mobile\controller;

use think\Controller;

class Association extends Controller{

    public  function index(){
        return $this->fetch();
    }

    public function my_association(){
        return $this->fetch();
    }

    public function assmember(){
        return $this->fetch();
    }
}