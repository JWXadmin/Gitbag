<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 12:06
 */
/*课程*/
namespace app\index\controller;

class Course extends Base{

    public function index(){
        $bbs=db('bbs')->where('status',1)->order('last_time','desc')->paginate(10);
        $this->assign('bbs',$bbs);
        $this->assign('action','论坛');
        //获取三个最新发布的活动
        $hot_actives=db('active')->cache(true)->where('status',1)->order('date','desc')->limit(3)->select();
        $this->assign('hot_actives',$hot_actives);
        return $this->fetch();
    }

    public function course_details(){

        $bbs_id=input('bbs_id');
        if(!$bbs_id){
            $this->redirect('index');
        }
        $bbs=db('bbs')->cache(true)->where('bbs_id',$bbs_id)->where('status',1)->find();
        db('bbs')->where('bbs_id',$bbs_id)->update(['hits'=>$bbs['hits']+1]);
        if(!$bbs){
            $this->redirect('index');
        }
        $this->assign('content',$bbs);
        $this->assign('action','论坛');
        //获取最新的4篇文章
        $four=db('article')->cache(true)->where(['status'=>1])->order('date','desc')->limit(4)->select();

        $this->assign('four',$four);
        //获取改文章的所有评论
        $comments=db('comment')
            ->where(['status'=>1,'article_id'=>$bbs_id,'mould_id'=>4])
            ->order('date','desc')
            ->paginate(5)
            ->each(function($item,$key){
                $user_info=db('user')->where('userid',$item['userid'])->find();
                $item['user_info']=$user_info;
                return $item;
            });
        $comments_num=db('comment')->where(['status'=>1,'article_id'=>$bbs_id,'mould_id'=>4])->count();
        $this->assign('comments_num',$comments_num);
        $this->assign('comments',$comments);
        return $this->fetch();
    }

    public function make_course(){
            $this->assign('action','论坛');
            return $this->fetch();
    }

    public function make_course_in(){

        $data=input('post.');
        $userid=cookie('userid');
        if (!$userid){
            $back['status']=0;
            $back['msg']="你还没有登录";
            return $back;
        }
        $info['date']=time();
        $info['last_time']=time();
        $info['author_id']=cookie('userid');
        $info['author']=cookie('username');
        $info['bbs_title']=$data['title'];
        $info['bbs_content']=$data['content'];
        $info['thumb']="/static/new/upload/hot_bbs.jpg";
        $res=db('bbs')->insert($info);
        if ($res){
            $id=db('bbs')->getLastInsID();
            db('bbs')->where('bbs_id',$id)
                ->update(['link'=>'http://www.xiaokucao.com/index/course/course_details/bbs_id/'.$id.'.html']);
            $back['status']=1;
            $back['msg']='http://www.xiaokucao.com/index/course/course_details/bbs_id/'.$id.'.html';
            return $back;
        }else{
            $back['status']=2;
            $back['msg']="失败";
            return $back;
        }


    }
}