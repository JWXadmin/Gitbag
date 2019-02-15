<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

function unlimitedForLayer($cate, $name = 'child', $pid = 0)
{
    $arr = array();
    foreach ($cate as $v) {
        if ($v['pid'] == $pid) {
            $v[$name] = unlimitedForLayer($cate, $name, $v['id']);
            $arr[] = $v;
        }
    }
    return $arr;
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0, $adv = false)
{
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL) return $ip[$type];
    if ($adv) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

function getAllMenu($model){
    //从数据库读取菜单
    $cate = $model->where('status = 1')->order('id asc')->select();     //读取用作菜单显示的
    //var_dump($cate);die;
    $menu=unlimitedForLayer($cate);
    //var_dump($model->_sql());
    //var_dump($menu);die;
    return $menu;
}
/**
 * 上传文件
 * @param integer $labelname 标签名
 * @param boolean $filename  上传文件夹名
 * @param boolean $type      文件类型：PNG、JPEG、video
 * @return mixed
 */
function uploading($labelname,$filename,$type){
    $arr = array();
    $files = request()->file($labelname);
    if($files){
        if(is_array($files)){
            foreach($files as $file){
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->move(ROOT_PATH. 'public/upload/'.$filename.'/'.$type.'/');
                if($info){
                    // 成功上传后 获取上传信息
                    // 输出 jpg
//                $arr[''] $info->getExtension();
                    // 输出 42a79759f284b767dfcb2a0197904287.jpg
                    $arr[]['name'] = '/upload/'.$filename.'/'.$type.'/'.$info->getSaveName();
                }else{
                    // 上传失败获取错误信息
                    return [
                        'sta'=>201,
                        'data'=>$files->getError()
                    ];
                }
            }
        }else{
            $info = $files->move(ROOT_PATH. 'public/upload/'.$filename.'/'.$type.'/');
            if($info){
//                   echo 1;
                // 成功上传后 获取上传信息
                // 输出 jpg
//                $arr[''] $info->getExtension();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                $arr['name'] = '/upload/'.$filename.'/'.$type.'/'.$info->getSaveName();
            }else{
                // 上传失败获取错误信息
                return [
                    'sta'=>201,
                    'data'=>$files->getError()
                ];
            }
        }
        return [
            'sta'=>200,
            'data'=>$arr
        ];
    }else{
        return [
            'sta'=>202,
            'data'=>'无上传文件'
        ];
    }
}

function getip() {
    $unknown = 'unknown';
    if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown) ) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif ( isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown) ) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    /*
    处理多层代理的情况
    或者使用正则方式：$ip = preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : $unknown;
    */
    if (false !== strpos($ip, ','))
        $ip = reset(explode(',', $ip));
    return $ip;
}

