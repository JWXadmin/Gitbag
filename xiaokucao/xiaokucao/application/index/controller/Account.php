<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/25
 * Time: 13:09
 */
/*这个是用户的个人中心，显示用户的个人信息*/
namespace app\index\controller;
use think\Cookie;
use think\Request;

class Account extends Base{

    public function _initialize()
    {
        $user_id=cookie('userid');
        if(!$user_id){
            $this->redirect('index/index/index');
        }
    }

    public function index(){
        //获取当前用户的个人资料
        $userid=cookie('userid');
       $userinfo=db('user')->where('userid',$userid)->find();
        $this->assign('action','个人中心');
        $this->assign('userinfo',$userinfo);
        return $this->fetch();
    }

    public function edit_userinfo(){
        $data=input('post.');
       //如果用户没有输入新的邮箱，那么就是不更改邮箱
        if ($data['email']==''){
            unset($data['email']);
        }

        //用户输入了新的密码
        if (trim($data['my_password'])==''){
            unset($data['my_password']);
            unset($data['my_repassword']);
        }else{
            if(trim($data['my_password'])!=trim($data['my_repassword'])){
                $back['status']=0;
                $back['msg']="对不起，两次密码不一致";
                return $back;
            }
            //更新用户的密码
            $user_info=db('user')->where('userid',cookie('userid'))->find();
            $data['password']=md5(trim($data['my_password'])).$user_info['salt'];
            unset($data['my_repassword']);
            unset($data['my_password']);

        }
    //return $data;
        //开始更新用户的个人信息
        $res=db('user')->where('userid',cookie('userid'))->update($data);

        if($res){
            $back['status']=1;
            $back['msg']="修改个人信息成功";
        }else{
            $back['status']=0;
            $back['msg']="修改个人信息失败，请稍后再试";
        }

        return $back;
    }

    public function upload(){

        $file=request()->file('avator');

        $data=array();
        //存储照片
        if($file){
            $info=$file->move(ROOT_PATH.'public'.DS.'uploads');
            if($info){
                $data['avator']=$info->getSaveName();
            }else{
                $back['status']=0;
                $back['msg']="上传遇到错误，请稍后再试";
                return $back;
            }
        }
        //写入数据库
        $res=db('user')->where('userid',cookie('userid'))->update($data);
        if($res){
            $back['status']=1;
            $back['msg']=$data['avator'];
        }else{
            $back['status']=0;
            $back['msg']="上传遇到错误，请稍后再试";
        }
        return $back;
    }

    public function edit_avator(){
        $data=input('post.');
        $res=db('user')->where('userid',cookie('userid'))->update(['avator'=>$data['base64']]);
        if ($res){
            $back['status']=1;
            $back['msg']='上传头像成功了';
        }else{
            $back['status']=0;
            $back['msg']='上传遇到错误，稍后再试';
        }
        return $back;
    }
    public function title_img(Request $request){
        $file 	= $request->file('file');
//        $info 	= $file->move(ROOT_PATH . 'public' . DS . 'uploads'.DS.'home');
        $info   = $file->move(ROOT_PATH.'public'.DS.'uploads');
        if($info){
            $name_path =str_replace('\\',"/",$info->getSaveName());

            $result['data']["src"] = "/upload/layui/".$name_path;
            $url 	= $info->getSaveName();
            //图片上传成功后，组好json格式，返回给前端
            $arr   = array(
                'code' => 0,
                'message'=>'图片上传成功!',
                'src'  => DS.'uploads'.DS.$name_path,
//                'url'  =>$url,
            );
            exit(json_encode($arr));
        }else{
            exit(json_encode(12121212121));
        }
    }
    public function editTitleImg(){

        $img    =  $this->request->only('img');
//        exit(json_encode($img));
        $res=db('user')->where('userid',cookie('userid'))->update(['avator_img'=>$img['img']]);
        if ($res){
            exit(json_encode(['code'=>1,'msg'=>'保存成功']));
        }else{
            exit(json_encode(['code'=>0,'msg'=>'保存失败']));
        }
    }


}