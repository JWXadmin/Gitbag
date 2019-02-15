<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/16
 * Time: 23:22
 */
namespace  app\index\controller;
use think\Controller;
use think\Request;
use think\Cookie;
class Base extends Controller{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        //检查用户是否登陆
        if(Cookie::get('username')){
            $this->assign('username',cookie('username'));
        }else{
            $this->assign('username',"no_username");
        }
    }
}