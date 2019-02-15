<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/16
 * Time: 21:29
 */
/*登录*/
namespace app\index\controller;
use think\Db;
use think\Controller;
use think\Cookie;
class Login extends Controller{

    public function login(){

        $data=input('post.');
        //检查用户名是否可用
        $check_username=db('user')->where('username',trim($data['username']))->find();
        if(!$check_username){
            $back['status']=0;
            $back['msg']="用户名或者密码错误";
            return $back;
        }
        //用户密码加密规则：密码md5()一次，拼接上salt
        if(md5(trim($data['password'])).$check_username['salt']!=$check_username['password']){
            $back['status']=0;
            $back['msg']="用户名或者密码错误";
            return $back;
        }
        //检测用户是否可以登录
        if($check_username['status']==0){
            $back['status']=0;
            $back['msg']="该账号不可登陆，请联系管理员";
            return $back;
        }

        setcookie('username',$check_username['username'],86400);
        setcookie('userid',$check_username['userid'],86400);
        Cookie::set('username',$check_username['username'],86400);
        Cookie::set('userid',$check_username['userid'],86400);

        $back['status']=1;
        $back['msg']="登陆成功";
        return $back;


    }

    public function register(){

        $data=input('post.');
        //检查用户输入的用户名是否可用
        $check=db('user')->where('username',trim($data['usernames']))->find();
        if($check){
            $back['status']=0;
            $back['msg']="该用户名不可用";
            return $back;
        }
        $salt=md5(rand(10000,99999));
        //写入用户的注册信息
        $infomation=array();
        $infomation['username']=trim($data['usernames']);
        $infomation['password']=md5(trim($data['passwords'])).$salt;
        $infomation['registertime']=time();
        $infomation['logintime']=time();
        $infomation['salt']=$salt;
        //return $infomation;
        //写入数据库
        $res=db('user')->insert($infomation);
        if (!$res){
            $back['status']=0;
            $back['msg']="注册失败";
            return $back;
        }else{

            Cookie::set('username',trim($data['usernames']),7200);
            Cookie::set('userid',$userId = Db::name('user')->getLastInsID(),7200);
            //更新该用户的访问记录，查询此ip地址最近的一次访问记录
            $user_log=db('user_log')->where(['status'=>1,'user_ip'=>cookie('user_ip')])->order('date','desc')->find();
            db('user_log')->where('log_id',$user_log['log_id'])->update(['userid'=>cookie('userid'),'username'=>cookie('username')]);

            $back['status']=1;
            $back['msg']='注册成功';
            return $back;
        }


    }

    public function loginouts(){

        Cookie::set('username','',-1);
        Cookie::set('userid','',-1);
        Cookie::set('user_ip','',-1);

        return 1;
    }
}