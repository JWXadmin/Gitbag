<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5
 * Time: 12:41
 */
namespace app\mobile\controller;
use think\Controller;

class Contact extends Controller{

    public function index(){

        return  $this->fetch();
    }
}