<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/11
 * Time: 22:19
 */
namespace app\admin\controller;
class Web extends Base{

    public function lists(){
        return $this->fetch();
    }

    public function user_log(){
        $user_logs=db('user_log')->order('date','desc')->paginate(15);
        $this->assign('user_logs',$user_logs);
        return $this->fetch();
    }

    public function lunbo(){
        $lunbos=db('lunbo')->paginate(10);
        $this->assign('lunbo',$lunbos);
        return $this->fetch();
    }

    public function addlunbo(){
        if(request()->isPost()){

            $title=input('title');
            $file=request()->file('picture');
            if($file){
                $info=$file->move('public'.DS.'uploads');
                if ($info){
                    $picture='/public/uploads/'.$info->getSaveName();
                    $picture=str_replace("\\","/",$picture);
                }else{
                    $this->error("添加轮播图遇到错误");
                }
            }else{
                $this->error("请选择图片");
            }
            $res=db('lunbo')->insert(['title'=>trim($title),'picture'=>$picture,'date'=>time()]);
            if ($res){
                $this->success('添加轮播图成功','lunbo');
            }else{
                $this->error("添加轮播图遇到错误");
            }
        }else{
            return $this->fetch();
        }
    }

    public function ads(){
        $ads=db('ads')->order('date','desc')->paginate(15);
        $this->assign('ads',$ads);
        return $this->fetch();
    }

    public function lunbo_status(){
        $lunbo_id=input('lunbo_id');
        $status=input('status');
        $res=db('lunbo')->where('lunbo_id',$lunbo_id)->update(['status'=>$status]);
        $this->success('修改成功');

    }
}