<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5
 * Time: 15:16
 */
namespace app\mobile\controller;
use think\Controller;


class Enterprise extends Controller{

    public function index(){

        return $this->fetch();
    }

    public function enterprise_detail(){

        return $this->fetch();
    }
}