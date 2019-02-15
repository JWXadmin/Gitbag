<?php

namespace app\admin\controller;

use app\admin\model\Admin;
use app\admin\model\AuthGroup;
use app\admin\model\AuthGroupAccess;
use think\Controller;

session_start();

class Login extends controller
{

    protected $AuthGroup;
    protected $AuthGroupAccess;
    protected $Admin;
    protected $database;


    public function __construct()
    {
        parent::__construct();
        $this->AuthGroup = new AuthGroup();
        $this->AuthGroupAccess = new AuthGroupAccess();
        $this->Admin = new Admin();
        $this->database = config("database.database");
    }

    public function index()
    {
        return $this->fetch();
    }

    /*
* 登录数据管理
*
* */
    public function login()
    {
        //表单数据接收
        $user = input('post.logname');#用户
        $password = input('post.logpass');#密码
        //模型数据库查询
        $sid = $this->Admin->where(array('uname' => $user))->column('sid')[0];

        if (empty($sid)) {
            $data = array(
                'sta' => false,
                'tip' => '登录失败,请稍后重试'
            );
        } else {
            $userL = $this->Admin
                ->alias('a')
                ->join($this->database.'.permission_auth_group_access ga', 'a.id = ga.uid')
                ->join($this->database.'.permission_auth_group g', 'g.id = ga.group_id')
                ->where(['a.uname' => $user, 'a.pwd' => md5($password . $sid)])
                ->find();
            /*echo "<pre>";
            print_r($userL);
            exit;*/
            //登录逻辑处理
            if (!$user or !$password) {
                #用户名或者密码有空
                $data = array(
                    'sta' => false,
                    'tip' => '用户名或者密码不能留空, 请检查输入'
                );
            } else {
                if ($userL === NULL) {
                    #用户名或者密码错误
                    $data = array(
                        'sta' => false,
                        'tip' => '用户名或者密码有误'
                    );
                } else{
                    if ($userL === false) {
                        #模型错误
                        $data = array(
                            'sta' => false,
                            'tip' => '登录失败,请稍后重试'
                        );
                    } else {
                        if ($userL['status'] != 1){
                            $data = array(
                                'sta' => false,
                                'tip' => '该账号已被封锁'
                            );
                        } else {
                            #登录成功
                            $data = array(
                                'sta' => true,
                            );
                            #保存登录数据至cookie
                            $session_arr = array(
                                'aid' => $userL['sid'],
                                'uid' => $userL['id'],
                                'adminname' => $userL['nikename']
                            );
                            session('sign_admin', $session_arr);

                        }
                    }
                }
            }
        }

        #返回ajax json数据
        return json($data);
    }

    /*
     * 退出登录
     * */
    public function logout()
    {
        session('sign_admin', null);
        session_destroy();

        $this->redirect('login/index');
    }
}