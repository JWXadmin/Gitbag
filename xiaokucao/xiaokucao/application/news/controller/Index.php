<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/16
 * Time: 17:09
 */
namespace app\news\controller;

use think\Controller;

class Index extends Controller{
    public function index(){

           return $this->fetch();
    }
}