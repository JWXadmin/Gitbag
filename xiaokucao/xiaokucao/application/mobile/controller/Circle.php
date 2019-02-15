<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5
 * Time: 16:07
 */
namespace app\mobile\controller;
use think\Controller;


class Circle extends Controller{

    public function index(){

        return $this->fetch();
    }

   public function circlepublish(){

        return $this->fetch();
   }

   public function circle_2(){

        return $this->fetch();
   }



}