<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/22
 * Time: 17:32
 */
namespace app\admin\controller;

class Bbs extends Base{

    public function lists(){
        $datas=db('bbs')->order('date','desc')->paginate(15);
        $this->assign('bbs',$datas);
        return $this->fetch();
    }

    public function change_status(){
        $bbs_id=input('bbs_id');
        $status=input('status');
        db('bbs')->where('bbs_id',$bbs_id)->update(['status'=>$status]);
        $this->success('修改成功');
    }
}