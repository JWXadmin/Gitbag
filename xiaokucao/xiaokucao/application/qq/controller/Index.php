<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/6
 * Time: 9:36
 */
namespace app\qq\controller;

use think\Controller;

class Index extends  Controller{

    public function index(){
        $data=input('');
        print_r($data);
    }

    public function login(){
        require_once("../extend/API/qqConnectAPI.php");
        $qc = new \QC();
        $qc->qq_login();

    }
}