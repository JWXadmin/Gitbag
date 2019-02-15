<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5
 * Time: 14:30
 */
namespace app\mobile\controller;
use think\Controller;

class Login extends Controller{

    public function index(){

        return $this->fetch();
    }

    public function register(){
        return $this->fetch();
    }

    public function resetpassword(){
        return $this->fetch();
    }

    public function resetpassword_1(){
        return $this->fetch();
    }

    public function resetpassword_2(){
        return $this->fetch();
    }
}