<?php

namespace app\admin\controller;
class User extends Base
{
    public function userlist1(){
        $map = array();
        $table = db('user');
        if (trim(input('nickname'))) {
            $map['nickname'] = array('LIKE',"%".trim(input('nickname'))."%");
        }
        if (trim(input('phone'))) {
            $map['phone'] = trim(input('phone'));
        }
        if (trim(input('user_id'))) {
            $map['user_id'] = trim(input('user_id'));
        }
        if (trim(input('sex'))) {
            $map['sex'] = trim(input('sex'));
        }
        $user = $table->where($map)->paginate(10);
        $this->assign('user',$user);
        return  $this->fetch();
    }

    public function userlist(){
       $user=db('user')->paginate(10);
        $this->assign('user',$user);
        return  $this->fetch();
    }

    public function user_status(){

        $id = input('id');
        $status = input('status');
        db('user')->where(['userid'=>$id])->update([
            'status'=>$status
        ]);
        $this->success('修改成功');
    }

    public function useredit(){
        $id = input('id');
        $info = db('user')->where(['userid'=>$id])->find();
        $this->assign('info',$info);
        return $this->fetch();
    }

    public  function doUserEdit(){
        return $this->redirect('userlist');
        /*$id = input('id');
        $data = input('');
        unset($data['id']);
        if (!$id){
            $this->error('参数错误');
        }else{
            $re = db('user')->where(['id'=>$id])->update($data);
        }
        if ($re){
            $this->success('修改成功');
        }else{
            $this->error('修改失败');
        }*/

    }
}