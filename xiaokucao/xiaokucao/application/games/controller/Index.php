<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/31
 * Time: 12:45
 */
namespace app\games\controller;

use app\admin\controller\Base;

class Index extends Base{

    public function index(){
        return $this->fetch();
    }


}