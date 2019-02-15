<?php

namespace app\admin\controller;
use app\admin\model\Category as Catemodel;
use think\Request;


class Cate extends Base
{
   public function index(){

       return $this->fetch();
   }
   public function lists(){
       //首先是在数据提取数据
       $list = db('cates')->order('date', 'desc')->paginate(15);
       $this->assign('cates', $list);
       return $this->fetch();
   }
   public function addcate(){
           return $this->fetch();
   }
    public function doaddcate(){
       //接收数据
        $data['cate_name'] = input("cate_name");
        if(!trim($data['cate_name'])){
             $this->error('请输入分类名称');
        }
        $data['date']=time();
       db('cates')->insert($data);
        $this->success('添加分类成功','lists');
    }
    public function doedit(){
            $date=input('');
        $user = new Catemodel();
        // save方法第二个参数为更新条件
        $user->save([
            'name'  => $date['name'],
            'order' => $date['order'],
            'ischarge' => $date['ischarge'],
            'money' => $date['money'],
        ],['id' => $date['id']]);

        $this->success("编辑成功",'cate/lists');
    }
    public function delete(){
       $date=input('id');
       //更改类别的状态就可以实现
        $user = new Catemodel();
        // save方法第二个参数为更新条件
        $user->save([
            'status'  => 0,

        ],['id' => $date]);

        $this->success("删除成功",'cate/lists');
    }

}