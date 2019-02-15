<?php

namespace app\admin\controller;


class Index extends Base
{

    public function index()
    {
        return $this->fetch();

    }

    public function map()
    {
        return $this->fetch();
    }

    public function details()
    {
        //日

        $today = strtotime(date('Y-m-d', time()));
        $nextday = strtotime(date('Y-m-d', strtotime("+1 day")));

        if (trim(input('time'))) {
            $today = strtotime(trim(input('time')));
        }
        if (trim(input('endtime'))) {
            $nextday = strtotime(trim(input('endtime')));
        }

        $show_time = date('Y-m-d', strtotime(trim(input('time'))));
        $show_rndtime = date("Y-m-d", strtotime(trim(input('endtime'))));

        $arr = array();
        $arr['today']['user'] = db("user")->where("add_time >= {$today} and add_time <= {$nextday}")->count();
        $arr['today']['recharge'] = db("recharge")->where("add_time >= {$today} and add_time <= $nextday")->sum("amount");
        $arr['today']['order_money'] = db("order_pay")->where("add_time >= {$today} and add_time <= $nextday")->sum("money");
        $arr['today']['chaoshi'] = db("order")->where("send_time >= {$today} and send_time <= {$nextday} and status = 7")->count();
        $arr['today']['lose'] = db("order")->where("send_time >= {$today} and send_time <= {$nextday} and status = 6")->count();

        if (input("time") || input("endtime")) {
            $arr['today']['time'] = $show_time . '——' . $show_rndtime;
        } else {
            $arr['today']['time'] = '今日';
        }

        //月


        $arr['all']['user'] = db("user")->count();
        $arr['all']['recharge'] = db("recharge")->sum("amount");
        $arr['all']['order_money'] = db("order_pay")->sum("money");
        $arr['all']['chaoshi'] = db("order")->where("status = 7")->count();
        $arr['all']['lose'] = db("order")->where("status = 6")->count();
        $arr['all']['time'] = '总计';
//        echo "<pre>";
//        print_r($arr);
//        echo "<pre/>";
        $this->assign('arr', $arr);
        return $this->fetch();
    }

    public function null()
    {
        return $this->fetch();
    }

}