<?php

namespace app\admin\controller;
use app\admin\model\VideoList as Videolist;
use app\admin\model\Category as Catemodel;
use think\Request;


class Video extends Base
{

   public function lists(){
       //视频列表展示
       $data=db('videos')->order('date','desc')->paginate(10);
       $this->assign('videos',$data);
       return $this->fetch();
   }

   public function upload(){
       if(request()->isPost()){
           $data=array();
           //获取视频传输的内容
           $file=request()->file('video');

           if($file){
               $info=$file->move(ROOT_PATH.'public'.DS.'uploads'.DS.'videos');
               if($info){
                   $data['video_url']='/uploads/videos/'.$info->getSaveName();
               }else{
                   $this->error('上传视频遇到错误');
               }
           }else{
               $this->error('对不起，请选择你要上传的视频');
           }
           $info=input('post.');
           $data['video_name']=trim($info['video_name']);
           $data['author']=trim($info['author']);
           $data['content']=trim($info['content']);
           $data['date']=time();
           //获取商品的类别名称
           $cate=db('cates')->where('cate_id',$info['cate_id'])->find();
           $data['cate_id']=$info['cate_id'];
           $data['cate_name']=$cate['cate_name'];
           $data['thumb']="/static/new/upload/video1.jpg";

           $res=db('videos')->insert($data);

           $data_Id = db('videos')->getLastInsID();
           db('videos')->where('video_id',$data_Id)->update(['link'=>"http://www.xiaokucao.com/index/community/community_post/video_id/".$data_Id.".html"]);

           if ($res){
               $this->success('添加视频成功','lists');
           }else{
               $this->error('添加视频失败');
           }



       }else{
           $cates=db('cates')->select();
           $this->assign('cates',$cates);
           return $this->fetch();
       }
   }

   public function see_video(){
       $video_id=input('video_id');
       if(!$video_id){
           $this->redirect('lists');
       }
       $video=db('videos')->where('video_id',$video_id)->find();
       $this->assign('video',$video);
       return $this->fetch();
   }

   public function lunbo(){
       $lunbos=db('video_lunbo')->where('status',1)->paginate(10);
       $this->assign('lunbos',$lunbos);
       return $this->fetch();
   }

   public function add_video_lunbo(){
       if(request()->isPost()){

           $file=request()->file('thumb');
           if($file){
               $info=$file->move(ROOT_PATH.'public'.DS.'uploads'.DS.'videos');
               if($info){
                   $data['thumb']='/uploads/videos/'.$info->getSaveName();
                   $data['thumb']=str_replace("\\","/",$data['thumb']);
               }else{
                   $this->error('上传视频封面遇到错误');
               }
           }else{
               $this->error('对不起，请选择你要上传的视频封面');
           }

           $data['video_url']=input('video_url');
           $data['date']=time();
           $res=db('video_lunbo')->insert($data);
           if($res){
               $this->success('添加轮播视屏成功','lunbo');
           }else{
               $this->error('添加视频轮播遇到错误');
           }

       }else{
           return $this->fetch();
       }
   }


}