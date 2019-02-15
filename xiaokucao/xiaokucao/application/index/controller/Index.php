<?php
/*首页*/
namespace app\index\controller;

use think\Controller;
use think\Cookie;
use think\Db;
class Index extends Base
{
    public function index()
    {
        //当有用户进入网站的的时候，获取用户的ip
        $ip=getip();
        //保存用户的访问记录,用户访问时间，有效时间为一个小时，超出一个小时算第二个用户
        if(!cookie('user_ip')){
            Cookie::set('user_ip',$ip,7200);
            $log['date']=time();
            if (cookie('username')){
                $log['username']=cookie('username');
                $log['userid']=cookie('userid');
            }else{
                $log['username']='游客';
                $log['userid']=0;
            }
            $log['user_ip']=$ip;
            db('user_log')->insert($log);
        }

        //获取当前所有的文章，分为点击量最高的10篇文章
        $hot_articles=db('article')->cache(true)->where('status',1)->order(['date'=>'desc'])->select();
        //获取每一篇文章的评论数
        foreach ($hot_articles as $k=>$v){
            $num=db('comment')->cache(true)->where('article_id',$v['article_id'])->where('mould_id',1)->where('status',1)->count();
            $hot_articles[$k]['nums']=$num;
        }
        $this->assign('hot1',$hot_articles[0]);
        $this->assign('hot2',$hot_articles[1]);
        $this->assign('hot3',$hot_articles[2]);
        $this->assign('hot4',$hot_articles[3]);
        $this->assign('hot5',$hot_articles[4]);
        $this->assign('hot6',$hot_articles[5]);
        $this->assign('hot7',$hot_articles[6]);
        $this->assign('hot8',$hot_articles[7]);
        $this->assign('hot9',$hot_articles[8]);
        $this->assign('hot10',$hot_articles[9]);
        $this->assign('action','首页');

        $hot_actives=db('active')->cache(true)->where('end_time','>',time())->where('status',1)->order('date','desc')->limit(6)->select();
        foreach ($hot_actives as $k=>$v){
            $num=db('comment')->cache(true)->where('article_id',$v['active_id'])->where('mould_id',3)->where('status',1)->count();
            $hot_actives[$k]['nums']=$num;
        }
        $this->assign('hot_actives',$hot_actives);

        //獲取輪播圖
        $lunbo=db('lunbo')->cache(true)->where('status',1 )->select();
        $this->assign('lunbo',$lunbo);


        return $this->fetch('index');
    }

    public function search(){

        //这里组装用户输入的关键字，多表联合查询并分页

        $where=strip_tags(trim(input('search')));
        //下面已经被注释的语句存在漏洞，会被sql注入
        /*if ($where="'"){
            $articles=array();
        }else{
            $articles= Db::field('video_name as title,link,author,hits,date,thumb')
                ->table('xkc_videos')->where('video_name','like','%'.$where.'%')
                ->union("SELECT title,link,author,hits,date,thumb FROM xkc_article where title like '%{$where}%'")
                ->union("SELECT active_name as  title,link,author,hits,date,thumb FROM xkc_active where active_name like '%{$where}%'")
                ->union("SELECT bbs_title as  title,link,author,hits,date,thumb FROM xkc_bbs where bbs_title like '%{$where}%'")
                ->select();
        }*/
        /*$articles= Db::field('video_name as title,link,author,hits,date,thumb')
            ->table('xkc_videos')->where('video_name','like','%'.$where.'%')
            ->union("SELECT title,link,author,hits,date,thumb FROM xkc_article where title like '%{$where}%'")
            ->union("SELECT active_name as  title,link,author,hits,date,thumb FROM xkc_active where active_name like '%{$where}%'")
            ->union("SELECT bbs_title as  title,link,author,hits,date,thumb FROM xkc_bbs where bbs_title like '%{$where}%'")
            ->select();*/
        $articles1=db('videos')->field('video_name as title,link,author,hits,date,thumb')
            ->where('video_name','like','%'.$where.'%')
            ->where('status',1)->select();

        $articles2=db('article')->field(' title,link,author,hits,date,thumb')
            ->where('title','like','%'.$where.'%')
            ->where('status',1)->select();
        $articles3=db('active')->field('active_name as title,link,author,hits,date,thumb')
            ->where('active_name','like','%'.$where.'%')
            ->where('status',1)->select();
        $articles4=db('bbs')->field('bbs_title as title,link,author,hits,date,thumb')
            ->where('bbs_title','like','%'.$where.'%')
            ->where('status',1)->select();
        $articles=array_merge($articles1,$articles2,$articles3,$articles4);
        $this->assign('where',$where);
        $this->assign('articles',$articles);
        $this->assign('action','搜索');
        //获取最新的4篇文章
        $four=db('article')->where(['status'=>1])->order('date','desc')->limit(4)->select();
        $this->assign('four',$four);
        return $this->fetch();
    }


}
