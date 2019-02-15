<?php

namespace app\admin\controller;

use app\admin\model\Admin;
use app\admin\model\AuthGroup;
use app\admin\model\AuthGroupAccess;
use app\admin\model\AuthRule;

use think\Controller;
use think\Jump;
class Base extends controller
{

    protected $AuthGroup;
    protected $AuthGroupAccess;
    protected $Admin;
    protected $AuthRule;
    protected  $database;
    /**
     * 空控制器
     */
    public function _empty()
    {
        $content="
<!DOCTYPE html>
<html>
<head>
</head>
<body >
<center><h1>歡迎使用後台管理系統</h1></center>
<br>
<br>
<br>
<center><p>此平台版權歸蘭台所有，侵權必究！</p></center>


</body>
</html>
";
        exit($content);
    }

    /**
     * 默认执行
     */
    protected function _initialize()
    {
        $this->AuthGroup = new AuthGroup();
        $this->AuthGroupAccess = new AuthGroupAccess();
        $this->Admin = new Admin();
        $this->AuthRule = new AuthRule();
        $this->database = config("database.database");
        //检测IP黑名单
        //$this->isBlackIP();
        //
        $request = \think\Request::instance();
        $node = $request->controller() . '/' . $request->action();
        if (!in_array($node, array('Index/login'))) {
            $this->checkSignin();
        }

        //检查权限
        $this->checkPower();
        //获取对应权限菜单栏
        $nav_data = $this->getTreeData('level', 'order_number,id');

        $this->assign('nav_data', $nav_data);
    }

    /**
     * 检测登陆
     */
    protected function checkSignin()
    {
        $sign_admin = session('sign_admin');
        if (empty($sign_admin)) {
            $this->redirect(url('Admin/Login/index'));
        }

    }

    /**
     * 检测权限
     */
    protected function checkPower($node = 0)
    {
        /*$power = session('sign_admin')['power'];
        if( $power == 0 ) {
            return true;
        } else if( ) {

        }*/

        $request = \think\Request::instance();
        $ctl = $request->controller();
        $act = $request->action();
        $auth_id = session('sign_admin')['uid'];  //接受session


        /*if($auth_id==null){
            //$this->redirect("/Home/index");
            $this->error('请先登录以后再操作!',U('/Home/index'));
        }*/

        //无需验证的操作
        $uneed_check = array('login', 'logout', 'vertifyHandle', 'vertify', 'imageUp', 'upload', 'login_task', 'user_upwd');
        $uneed_check_cont = array('Index', 'Main');

        if (in_array($ctl, $uneed_check_cont) || $auth_id == '1') {
            //后台首页控制器无需验证,超级管理员无需验证
            return true;
        } elseif (strpos($act, 'ajax') || in_array($act, $uneed_check)) {
            //所有ajax请求不需要验证权限
            return true;
        } else {
            $auth = new \think\Auth();
            if (!$auth->check($request->module() . '/' . $request->controller() . '/' . $request->action(), $auth_id)) {
                $this->error('没有权限');
            }else{
                return true;
            }
        }
        exit;
    }

    /**
     * 检测ip黑名单
     */
    protected function isBlackIP()
    {
        $cur_ip = get_client_ip();
        $blacklist = explode('|', config('black_list'));
        if (in_array($cur_ip, $blacklist)) {
            exit('您当前IP被限制浏览该站点！');
        }
    }

    /*protected function get($id)
    {
        $cur_ip = get_client_ip();
        $blacklist = explode('|', config('black_list'));
        if (in_array($cur_ip, $blacklist)) {
            exit('您当前IP被限制浏览该站点！');
        }
    }*/

    /**
     * 获取全部菜单
     * @param  string $type tree获取树形结构 level获取层级结构
     * @return array        结构数据
     */
    private function getTreeData($type = 'tree', $order = '')
    {
        // 判断是否需要排序
        if (empty($order)) {
            $data = $this->AuthRule->where(['ismenu' => 1])->select();
        } else {
            $order_arr = explode(',', $order);
            $data = $this->AuthRule->where(['ismenu' => 1])->order($order_arr[0] . ' asc,' . $order_arr[1] . ' asc')->select();
        }
        $group_id = $this->AuthGroupAccess->where(['uid'=>session('sign_admin')['uid']])->column("group_id")[0];
        // 获取树形或者结构数据
        if ($type == 'tree') {
            $data = \Org\Nx\Data::tree($data, 'name', 'id', 'pid');
        } elseif ($type = "level") {
            $data = \Org\Nx\Data::channelLevel($data, 0, '&nbsp;', 'id');

            if ($group_id != 1){
                // 显示有权限的菜单
                $auth = new \think\Auth();
                foreach ($data as $k => $v) {
                    if ($auth->check($v['name'], session('sign_admin')['uid'])) {
                        foreach ($v['_data'] as $m => $n) {
                            if (!$auth->check($n['name'], session('sign_admin')['uid'])) {
                                unset($data[$k]['_data'][$m]);
                            }
                        }
                    } else {
                        // 删除无权限的菜单
                        unset($data[$k]);
                    }
                }
            }

        }

        return $data;
    }

}