<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/27
 * Time: 10:34
 */
namespace app\admin\controller;

use think\Db;

class Menu extends Base{

    public function lists(){


       $datas= Db::table('permission_auth_rule')->where('ismenu',1)->order('id','asc')->select();
       //生成树形结构
        $menu=$this->make_tree($datas,0,1);
        $this->assign('menu',$menu);
        return $this->fetch();
    }
    public function make_tree($arr=array(),$pid=0,$level=1){
        static $tree=array();
        foreach ($arr as $k=>$v){
            if($v['pid']==$pid){
                $v['level']=$level;
                $tree[]=$v;
                $this->make_tree($arr,$v['id'],$level+1);
            }
        }
        return $tree;
    }

    public function add_menu(){
        if (request()->isPost()){

            $info=input('post.');
            $information['name']=trim($info['name']);
            $information['title']=trim($info['title']);
            $information['pid']=$info['pid'];
            $information['ismenu']=1;
            $information['type']=1;
            $res=Db::table('permission_auth_rule')->insert($information);
            if ($res){
                $this->success('添加菜单成功','lists');
            }else{
                $this->error('添加菜单失败');
            }
        }else{
            $auth_id = session('sign_admin')['uid'];
            if ($auth_id!=1){
                $this->error('没有权限！');
            }
            $datas= Db::table('permission_auth_rule')->where('ismenu',1)->order('id','asc')->select();
            //生成树形结构
            $menu=$this->make_tree($datas,0,1);
            $this->assign('menu',$menu);
            return $this->fetch();
        }


    }
}