<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/20
 * Time: 14:46
 */
namespace app\admin\controller;

class Active extends Base{


    public function lists(){
        //获取所有的活动数据
        $data=db('active')->order('date','desc')->paginate(15);
        $this->assign('actives',$data);

        $this->assign('time',time());
        return $this->fetch();
    }

    public function add(){
        if(request()->isPost()){
            $info=array();
            $file=request()->file('thumb');
            $infos=$file->move(ROOT_PATH.'public'.DS.'uploads');
            if($infos){
                $info['thumb']='/uploads/'.$infos->getSaveName();
            }else{
                $this->error('添加活动失败');
            }
            $data=input('post.');

            $info['start_time']=strtotime($data['start_time']);
            $info['end_time']=strtotime($data['end_time']);
            $info['date']=time();
            $info['author']=trim($data['author']);
            $info['active_name']=trim($data['title']);
            $info['content']=$data['content'];
            $info['cate_id']=$data['cate_id'];
            $info['address']=$data['address'];
            $info['active_fee']=$data['active_fee'];
            $info['cate_name']=db('cates')->where('cate_id',$data['cate_id'])->find()['cate_name'];

            $res=db('active')->insert($info);
            $data_Id = db('active')->getLastInsID();
            db('active')->where('active_id',$data_Id)->update(['link'=>"http://www.xiaokucao.com/index/active/active_details/active_id/".$data_Id.".html"]);

            if ($res){
                $this->success('添加活动成功','lists');
            }else{
                $this->error('添加活动失败');
            }


        }else{
            $cates=db('cates')->select();
            $this->assign('cates',$cates);
            return $this->fetch();
        }
    }

    public function active_status(){
        $active_id=input('active_id');
        $status=input('status');

        db('active')->where('active_id',$active_id)->update(['status'=>$status]);
        $this->success('操作成功1');
    }

    public function see_active_log(){
        $active_id=input('active_id');
        $data=db('active_log')->where('active_id',$active_id)->paginate(15);
        $this->assign('actives',$data);
        return $this->fetch();
    }
}