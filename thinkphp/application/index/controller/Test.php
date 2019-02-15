<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/22
 * Time: 16:06
 */

namespace app\index\controller;


class Test
{
    public function index(){
        ini_set('memory_limit', '-1'); //内存无限
        set_time_limit(0);
        //30852
        $data=   db("member_company_dr")->order('id')->limit(50000)->select();
        foreach ($data as $k=>$v){
            $id = db('member_company')->where("COMPANYTAX='{$v['COMPANYTAX']}'")->value('id');
            if(!$id){
                $insert['COMPANYNAME'] = $v['COMPANYNAME'];
                $insert['COMPANYTAX'] = $v['COMPANYTAX'];
                $res = db('member_company')->insert($insert);
                if($res){
                    db('member_company_dr')->where("id={$v['ID']}")->delete();
                }
            }else{
                db('member_company_dr')->where("id={$v['ID']}")->delete();
            }
        }
    }

}


// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);
// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}





$sql = "INSERT INTO MyGuests (firstname, lastname, email)
VALUES ('John', 'Doe', 'john@example.com')";
if ($conn->query($sql) === TRUE) {
    echo "新记录插入成功";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

if ($conn->query($sql) === true){
    echo "chenggong";

}else{
    echo "cuowu:".$sql."<br>".$conn->error;
}