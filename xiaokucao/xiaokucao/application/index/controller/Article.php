<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 11:11
 */
/*文章*/
namespace app\index\controller;

class Article extends Base{

    public function index(){
        //搜索点击量最高的两篇文章
        $hots=db('article')->cache(true)->where('status',1)->order(['hits'=>'desc'])->limit(2)->select();
        //搜索所有扥文章并分页
        $articles=db('article')->cache(true)->where('status',1)->order(['date'=>'desc'])->paginate(9)
        ->each(function ($item,$key){

            $comments_num=db('comment')->cache(true)->where(['status'=>1,'article_id'=>$item['article_id'],'mould_id'=>1])->count();
            $item['comments_num']=$comments_num;
            return $item;


        });

        //获取最新的4篇文章
        $four=db('article')->cache(true)->where(['status'=>1])->order('date','desc')->limit(4)->select();

        $this->assign('four',$four);
        $this->assign('action','文章');
        $this->assign('hots',$hots);
        $this->assign('articles',$articles);
        return $this->fetch();
    }

    public function article_details(){

        $article_id=input('article_id');
        if (!$article_id){
           $this->redirect('index');
        }

        //获取最新的4篇文章
        $four=db('article')->cache(true)->where(['status'=>1])->order('date','desc')->limit(4)->select();

        $this->assign('four',$four);
        //查询文章内容
        $content=db('article')->cache(true)->where('article_id',$article_id)->find();
        db('article')->where('article_id',$article_id)->update(['hits'=>$content['hits']+1]);
        $this->assign('content',$content);

        //获取改文章的所有评论
        $comments=db('comment')
            ->where(['status'=>1,'article_id'=>$article_id,'mould_id'=>1])
            ->order('date','desc')
            ->paginate(5)
        ->each(function($item,$key){
            $user_info=db('user')->where('userid',$item['userid'])->find();
            $item['user_info']=$user_info;
            return $item;
        });
        $comments_num=db('comment')->where(['status'=>1,'article_id'=>$article_id,'mould_id'=>1])->count();
        $this->assign('comments_num',$comments_num);
        $this->assign('comments',$comments);
        $this->assign('action','文章');
        return $this->fetch();

    }
}