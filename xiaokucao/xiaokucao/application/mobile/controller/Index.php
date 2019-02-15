<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5
 * Time: 11:16
 */

namespace app\mobile\controller;



use think\Controller;

class Index extends Controller {

    public function index(){
        return $this->fetch();
    }
}