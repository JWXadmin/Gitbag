<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5
 * Time: 12:36
 */
namespace app\mobile\controller;
use think\Controller;

class User extends Controller{

    public function index(){

        return $this->fetch();
    }

    public function userpu_3(){
        return $this->fetch();
    }
}