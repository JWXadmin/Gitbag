<?php
/**
 * Created by PhpStorm.
 * User: 35711
 * Date: 2018/12/5
 * Time: 11:45
 */

namespace app\fierApi\controller;
use think\Request;

class Home extends Base
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->appId = config('fierApi.app_id');
        $this->appSecret=config('fierApi.app_secret');
    }

    public function index(){
        halt($this->appId);

    }

}