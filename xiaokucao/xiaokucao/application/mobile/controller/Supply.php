<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5
 * Time: 15:41
 */
namespace app\mobile\controller;

use think\Controller;


class Supply extends  Controller{

    public function index(){
        return $this->fetch();
    }

    public function supply_detail(){
        return $this->fetch();
    }

    public function addpurinfo(){

        return $this->fetch();
    }
}