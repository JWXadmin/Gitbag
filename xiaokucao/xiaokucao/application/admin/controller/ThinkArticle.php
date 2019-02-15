<?php
/**
 * Created by PhpStorm.
 * User: 35711
 * Date: 2018/11/13
 * Time: 13:04
 */

namespace app\admin\controller;


class ThinkArticle extends Base
{
    public function index()
    {
        return $this->fetch('think_article/index');
    }

}