<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 12:07
 */

/*活动*/
namespace app\index\controller;

class Community extends Base{

    public function index(){

        //获取所有的视频
        $videos=db('videos')->cache(true)->where('status',1)->order('date','desc')->paginate('8')
        ->each(function ($item,$key){
            $comments_num=db('comment')->cache(true)->where(['status'=>1,'article_id'=>$item['video_id'],'mould_id'=>2])->count();
            $item['comments_num']=$comments_num;
            return $item;
        });
        $this->assign('videos',$videos);
        $this->assign('action','视频');
        //获取最新的4篇文章
        $four=db('article')->cache(true)->where(['status'=>1])->order('date','desc')->limit(4)->select();

        $this->assign('four',$four);

        //获取视频轮播图
        $lunbos=db('video_lunbo')->cache(true)->where('status',1)->select();
        $this->assign('lunbos',$lunbos);
        //获取10个小视频
        $ten_videos=db('videos')->cache(true)->where('status',1)->limit(10)->order('date','desc')->select();
        foreach ($ten_videos as $k=>$v){
            $ten_videos[$k]['comments_num']=db('comment')->cache(true)->where(['status'=>1,'article_id'=>$v['video_id'],'mould_id'=>2])->count();
        }
        $this->assign('ten_videos',$ten_videos);
        return $this->fetch();
    }

    public function community_post(){

        $video_id=input('video_id');
        if (!$video_id){
            $this->redirect('index');
        }

        //获取视频内容
        $video=db('videos')->where('video_id',$video_id)->cache(true)->find();
        //该视频的点击量加1
        db('videos')->where('video_id',$video_id)->update(['hits'=>$video['hits']+1]);
        $this->assign('video',$video);
        $this->assign('action','视频');
        //获取改文章的所有评论
        $comments=db('comment')
            ->where(['status'=>1,'article_id'=>$video_id,'mould_id'=>2])
            ->order('date','desc')
            ->paginate(5)
            ->each(function($item,$key){
                $user_info=db('user')->where('userid',$item['userid'])->find();
                $item['user_info']=$user_info;
                return $item;
            });

        $comments_num=db('comment')->where(['status'=>1,'article_id'=>$video_id,'mould_id'=>2])->count();
        $this->assign('comments_num',$comments_num);
        $this->assign('comments',$comments);

        //获取最新的4篇文章
        $four=db('article')->where(['status'=>1])->order('date','desc')->limit(4)->select();

        $this->assign('four',$four);
        return $this->fetch();
    }
}