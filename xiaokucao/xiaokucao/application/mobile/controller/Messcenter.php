<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/5
 * Time: 16:31
 */

namespace app\mobile\controller;

use think\Controller;

class Messcenter extends Controller{

    public function index(){
        return $this->fetch();
    }

    public function cardexchange(){

        return $this->fetch();
    }

    public function verifypass(){

        return $this->fetch();
    }

    public function eventreminder(){
        return $this->fetch();
    }

    public function eventdetails(){
        return $this->fetch();
    }

    public function notify(){

        return $this->fetch();
    }

    public function noticedetails(){

        return $this->fetch();
    }

    public function about_update(){

        return $this->fetch();
    }
}