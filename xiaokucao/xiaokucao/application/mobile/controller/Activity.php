<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5
 * Time: 17:58
 */
namespace app\mobile\controller;

use think\Controller;

class Activity extends Controller{

    public function index(){

        return $this->fetch();
    }

    public function active_detail(){

        return $this->fetch();
    }

    public function enterfor(){

        return $this->fetch();
    }
}