<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/11
 * Time: 17:08
 */
namespace app\index\controller;

class Comment extends Base{

    public function  add_comment(){
        //模块  文章id  评论类容
        //检查用户是否登录
        $username=Cookie('username');
        if(!$username){
            $back['status']=2;
            $back['msg']="登陆之后，才可以评论额！";
            return $back;
        }
        $data=input('post.');
        $info['userid']=cookie('userid');
        $info['username']=cookie('username');
        $info['date']=time();
        $info['content']=$data['content'];
        $info['mould_id']=$data['mould_id'];
        $info['article_id']=$data['article_id'];
        $res=db($data['table'])->insert($info);
        if ($res){
            $back['status']=1;
            $back['msg']="评论成功";
        }else{
            $back['status']=0;
            $back['msg']="评论失败";
        }
        return $back;

    }

    public function do_good_hits(){
        //好评数
    }

    public function do_bad_hits(){
        //差评数
    }
}