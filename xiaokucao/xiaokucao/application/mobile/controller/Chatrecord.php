<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5
 * Time: 12:46
 */
namespace app\mobile\controller;
use think\Controller;

class Chatrecord extends Controller{

    public function index(){
        return $this->fetch();
    }
}