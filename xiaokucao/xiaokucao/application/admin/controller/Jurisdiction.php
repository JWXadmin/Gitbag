<?php

namespace app\admin\controller;
use think\Db;
class Jurisdiction extends Base
{
    //角色管理列表
    public function user_list()
    {

        $orderlist = $this->AuthGroup
            ->order('id asc')
            ->paginate(10);
        $this->assign('orderlist', $orderlist);// 赋值分页输出
        return $this->fetch();
    }

    public function user_status(){

        $status = input("status");
        $id = input("id");
        $re = $this->AuthGroup->where(array("id"=>$id))->setField('status',$status);
//        $a = M()->getLastSql();
//        echo $a;
        if($re){
            $this->success('修改成功');
        }else{
            $this->error('修改失败');
        }
    }

    public function user_edit(){

        $id = input('id');

        $arr = getAllMenu($this->AuthRule);
        $str = $this->getTree('',$arr,0,$id);
        $this->assign('tree',$str);
        $this->assign('id',$id);
        return $this->fetch();
    }

    private function getTree($str = '' , $arr ,$num,$id=1){
        $info = $this->AuthGroup->where(array("id"=>$id))->find();
        $this->assign("info",$info);
        $rules = explode(",",$info['rules']);
        $string = '';
        if ($num > 0){
            $i = 1;
            for ($i;$i<=$num;$i++){
                $string .= '&nbsp;&nbsp;&nbsp;&nbsp;';
            }
            $string .= '|-';
        }
        $num++;
//        print_r($arr);exit;
        foreach ($arr as $k=>$v){
            if (in_array($v['id'],$rules)){
                $check = "checked=checked";
            }else{
                $check = '';
            }
            $str .= '<tr><td style="width: 100px;"><input type="checkbox" '.$check.' name="check['.$v['id'].']" value="'.$v['id'].'"/></td><td></td> <td>'.$string.$v['title'].'</td></tr>';
//            $str.=$v['title'];
            if (!empty($v['child'])){
                $str = $this->getTree($str,$v['child'],$num,$id);
            }
        }
        return $str;
    }

    public function change(){

        $rules = input("check/a");
        $id = input("id");
        $data['rules'] = implode(",",$rules);
        $data['title'] = input("title");
//        var_dump($data);exit;
        $re = $this->AuthGroup->where(array("id"=>$id))->update($data);
//        var_dump($re);
        if($re){
            $this->success('修改成功');
        }else{
            $this->error('修改失败');
        }
    }

    public function adduser(){

        $arr = getAllMenu($this->AuthRule);
        $str = $this->getTree('',$arr,0);
        $this->assign('tree',$str);
        return $this->fetch();
    }

    /*角色添加*/
    public function doUserAdd(){

        $rules = input("check/a");
        $data['rules'] = implode(",",$rules);
        $data['title'] = input("title");
        $re = $this->AuthGroup->insert($data);
        if($re){
            $this->success('添加成功');
        }else{
            $this->error('添加失败');
        }
    }

    public function delete(){

        $id = input("id");
        $result = $this->AuthGroupAccess->where(array("group_id"=>$id))->column("uid");
        if ($result){
            $this->error('该角色下有管理员,删除失败');
        }else{
            $re = $this->AuthGroup->where(array("id"=>$id))->delete();
            if ($re){
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
        }

    }

    public function member_list(){

        $orderlist =  $this->Admin->alias('a')
            ->field("a.*,g.*,r.title")
            ->join($this->database.".permission_auth_group_access g","g.uid = a.id")
            ->join($this->database.".permission_auth_group r","r.id = g.group_id")
            ->order('a.id asc')
            ->paginate(10);
        $this->assign('orderlist', $orderlist);// 赋值分页输出
        return $this->fetch();
    }

    public function addmember(){

        $group = $this->AuthGroup->field("title,id")->where(array("status"=>1))->select();
        $this->assign('group',$group);
        return $this->fetch();
    }

    /*添加管理员*/
    public function doAddMember(){

        $data = input("post.");

        /*unset($data['/admin/jurisdiction/doaddmember_html']);*/

        if ($data['pwd'] != $data['pwd_sure']){
            $this->error("两次密码不一致");
            return false;
        }else{
            unset($data['pwd_sure']);
            $data['sid'] = md5(time());
            $data['pwd'] = md5($data['pwd'].$data['sid']);
            $data['createtime'] = time();

        }
        $admin =  $this->Admin->where(array("uname"=>$data['uname']))->field("id")->find();
        if ($admin){
            $this->error("用户名已存在，添加失败");
        }
        $group_id = $data['group_id'];
        unset($data['group_id']);
        db()->startTrans();
        $add_id =  $this->Admin->insertGetId($data);
        if ($add_id){
            $re = $this->AuthGroupAccess->insert(array("uid"=>$add_id,"group_id"=>$group_id));
            if ($re){
                db()->commit();
                $this->success("添加成功");
            }else{
                db()->rollback();
                $this->error("添加失败");
            }
        }else{
            db()->rollback();
            $this->error("添加失败");
        }
    }

    public function delete_member(){

        $id = input("id");
        db()->startTrans();
        $re =  $this->Admin->where(array("id"=>$id))->delete();
        if ($re){
            $res = $this->AuthGroupAccess->where(array("uid"=>$id))->delete();
            if ($res){
                db()->commit();
                $this->success("删除成功");
            }else{
                db()->rollback();
                $this->error("删除失败");
            }
        }else{
            db()->rollback();
            $this->error("删除失败");
        }

    }

    public function editmember(){

        $id = input("id");
        $admin =  $this->Admin->alias('a')->field("a.*,g.group_id")->join($this->database.".permission_auth_group_access g","a.id = g.uid")->where(array("a.id"=>$id))->find();
        $group = $this->AuthGroup->field("title,id")->where(array("status"=>1))->select();
        foreach ($group as $k=>$v){
            $group[$k]['id'] = (int)$v['id'];
        }
        $admin['group_id'] = (int) $admin['group_id'];
        $this->assign('group',$group);
        $this->assign('admin',$admin);
        return $this->fetch();
    }

    public function doEdit1(){

        $data = input("post.");
        $id = $data['id'];
        $ctime =  $this->Admin->where(array("id"=>$id))->column("sid");
        $group_id = $data['group_id'];
        if ($data['pwd_sure']){
            unset($data['group_id']);
            unset($data['id']);
            unset($data['pwd_sure']);
            $data['pwd'] = md5($data['pwd'].$ctime[0]);

            db()->startTrans();
            if ($group_id){
                $res = $this->AuthGroupAccess->where(array("uid"=>$id))->update(array("group_id"=>$group_id));
            }else{
                $res = true;
            }
            $res = true;
            if ($res){
                $re =  $this->Admin->where(array("id"=>$id))->update($data);
                if ($re){
                    db()->commit();
                    $this->success("修改成功");
                }else{
                    db()->rollback();
                    $this->error("修改失败1");
                }
            }else{
                db()->rollback();
                $this->error("修改失败");
            }
        }else{
            $res = $this->AuthGroupAccess->where(array("uid"=>$id))->update(array("group_id"=>$group_id));
            $this->success("修改成功");
           /* if ($res){
                $this->success("修改成功");
            }else{
                $this->error("修改失败2");
            }*/
        }


    }

    public function doEdit(){

        $data = input("post.");
        $id = $data['id'];
        $ctime =  $this->Admin->where(array("id"=>$id))->column("sid");

        //第一步更新用户的基本信息
        $userinfo=array();
        $userinfo['nikename']=$data['nikename'];
        $userinfo['uname']=$data['uname'];
        $userinfo['pwd']= md5($data['pwd'] .$ctime[0]);

        $res1=Db::table('permission_admin')->where('id',$data['id'])->update($userinfo);

        //第二步，更新用户组

        $res2=Db::table('permission_auth_group_access')->where('uid',$data['id'])->update(['group_id'=>$data['group_id']]);

        $this->success('修改成功','member_list');
    }


}