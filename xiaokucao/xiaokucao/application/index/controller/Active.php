<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 12:00
 */
/*活动*/
namespace app\index\controller;

class Active extends Base{

    public function index(){
        $this->assign('action','活动');
        //获取所有的活动
        $actives=db('active')->where('end_time','>',time())->where('status',1)->order('date','desc')->paginate(12);

        $this->assign('actives',$actives);

        //
        $hot_actives=db('active')->where('end_time','>',time())->where('status',1)->order('date','desc')->limit(2)->select();
        $this->assign('hot_actives',$hot_actives);
        return $this->fetch();
    }

    public function active_details(){
        $active_id=input('active_id');
        if (!$active_id){
            $this->redirect('index');
        }

        //获取活动的相关信息
        $active=db('active')->where('active_id',$active_id)->find();
        $this->assign('active',$active);
        db('active')->where('active_id',$active_id)->update(['hits'=>$active['hits']+1]);
        $this->assign('action','活动');
        $comments=db('comment')
            ->where(['status'=>1,'article_id'=>$active_id,'mould_id'=>3])
            ->order('date','desc')
            ->paginate(5)
            ->each(function($item,$key){
                $user_info=db('user')->where('userid',$item['userid'])->find();
                $item['user_info']=$user_info;
                return $item;
            });

        $this->assign('comments',$comments);
        $comments_num=db('comment')->where(['status'=>1,'article_id'=>$active_id,'mould_id'=>3])->count();
        $this->assign('comments_num',$comments_num);
        return $this->fetch();
    }

    public function active_log(){


        $active_id=input('active_id');
        $userid=cookie('userid');
        if(!$userid){
            $back['status']=0;
            $back['msg']="对不起，你还没有登录";
            return $back;
        }

        $active=db('active')->where('active_id',$active_id)->find();

        $active_log=db('active_log')->where(['active_id'=>$active_id,'user_id'=>cookie('userid')])->find();
        if ($active_log){
            $back['status']=0;
            $back['msg']="对不起，你已经报名，请勿重复操作";
            return $back;
        }



        $res=db('active_log')->insert([
            'active_name'=>$active['active_name'],
            'active_id'=>$active['active_id'],
            'date'=>time(),
            'user_id'=>cookie('userid'),
            'user_name'=>cookie('username')
        ]);

        if ($res){
            $back['status']=1;
            $back['msg']="报名成功，请准时参加活动！";
            return $back;
        }else{
            $back['status']=0;
            $back['msg']="报名失败，请稍后再试！";
            return $back;
        }

    }
}