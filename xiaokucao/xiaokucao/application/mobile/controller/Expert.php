<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5
 * Time: 17:34
 */
namespace app\mobile\controller;

use think\Controller;

class Expert extends Controller{

    public function index(){

        return $this->fetch();
    }

    public function moreexpert(){

        return $this->fetch();
    }

    public function expert_detail(){

        return $this->fetch();
    }
}