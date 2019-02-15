<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5
 * Time: 15:34
 */
namespace app\mobile\controller;

use think\Controller;

class Product extends Controller{

    public function index(){

        return $this->fetch();
    }
}