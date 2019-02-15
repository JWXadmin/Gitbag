<?php
/**
 * Created by PhpStorm.
 * User: fier
 * Date: 2018/9/13
 * Time: 9:15
 */

namespace app\open\controller;
header('content-type:text/html;charset=utf-8');

use think\Controller;
use think\Session;

class WxFunctionController extends Controller
{
    /**
     * @param $url 接口
     * @param string $type 请求类型
     * @param string $res 返回数据
     * @param bool $arr post请求参数
     * @return mixed\
     */
    static function getCurl($url,$type,$res=false,$arr=false){
        //1.初始化
        $ch = curl_init();
        //2.设置粗略得参数[默认GET请求]
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        if ($type=='post'){//post请求
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);
        }
        //3.采集url
        $result = curl_exec($ch);

        //4.返回采集结果
        if($res=='json'){
            if (curl_errno($ch)){
//                print_r(curl_error($ch)) ;
                return curl_error($ch);
            }else{
//                var_dump( json_decode($result,true));
                return json_decode($result,true);
            }
        }
        //5.关闭
//        curl_close($ch);
    }

    /**
     * 获取 access_token
     * @param $appId
     * @param $appSecret
     * @return mixed ["access_token"=>"ACCESS_TOKEN","expires_in"=>7200]
     */
    static function getAccessToken($appId,$appSecret){
        //将access_token 存入session中
        $token = Session::get('access_token');
        $tokenTime = Session::get('token_time');
        if($token && $tokenTime>time()){
            return $token;
        }else{
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appId&secret=$appSecret";
            $jsonRes = self::getCurl($url,'get','json');
            $access_token = $jsonRes['access_token'];
            Session::set('access_token',$access_token);
            Session::set('token_time',time()+7000);
            return $access_token;
        }
    }

    /**
     * 获取微信服务器IP地址
     * @param $appId
     * @param $appSecret
     * @return mixed  ["ip_list"=>[ "127.0.0.1","127.0.0.2","101.226.103.0/25"]]
     */
    static function getServerIp($appId,$appSecret){
        $accessToken = self::getAccessToken($appId,$appSecret);
        $url         = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=$accessToken";
        $jsonRes     = self::getCurl($url,'get');
        $ipList = json_decode($jsonRes,true);
        return $ipList;
    }

    /**
     * 自定义菜单方法
     * @param $appId
     * @param $appSecret
     * @param $array
     * @return array
     */
    static function defineMenus($appId,$appSecret,$array){
        $accessToken = self::getAccessToken($appId,$appSecret);
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$accessToken;
        $postJson = urldecode(json_encode($array));
        $res = self::getCurl($url,'post','json',$postJson);
        return $res;
    }


    /**
     * 单文本回复
     * @param $postObj
     * @param $content
     * @return string
     */
    static function oneTextReplyMsg($postObj,$content){
        $toUser     = $postObj->FromUserName;
        $fromUser   = $postObj->ToUserName;
        $time       = time();
        $msgType    = 'text';//纯文本回复
        $template   = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[%s]]></MsgType><Content><![CDATA[%s]]></Content></xml>";
        return    sprintf($template,$toUser,$fromUser,$time,$msgType,$content);

    }

    /**
     * 多图文消息回复
     * @param $postObj
     * @param $arr
     * @return string
     */
    static function imgTextReplyMsg($postObj,$arr){
        $toUser     = $postObj->FromUserName;
        $fromUser   = $postObj->ToUserName;
        $template = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<ArticleCount>".count($arr)."</ArticleCount>
						<Articles>";
        foreach($arr as $k=>$v){
            $template .="<item>
							<Title><![CDATA[".$v['title']."]]></Title> 
							<Description><![CDATA[".$v['description']."]]></Description>
							<PicUrl><![CDATA[".$v['info_url']."]]></PicUrl>
							<Url><![CDATA[".$v['url']."]]></Url>
							</item>";
        }

        $template .="</Articles></xml> ";
        return sprintf($template, $toUser, $fromUser, time(), 'news');

    }

    /**
     * @param $appId
     * @param $appSecret
     * @param $arr 要群发得消息
     * @return mixed\
     */
    static function groupHairTextMsg($appId,$appSecret,$arr)
    {
        $accessToken = self::getAccessToken($appId,$appSecret);
        $url         = "https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=".$accessToken;
        $postJson    = urldecode(json_encode($arr));
        $res         = self::getCurl($url,'post','json',$postJson);
        return $res;
    }

    /**
     * 获取图片url
     * @param $appId
     * @param $appSecret
     * @param $arr
     */
    static function groupHairImgTextMsg($appId,$appSecret,$images=false)
    {
        $img_size=filesize(ROOT_PATH."upload/home/20180912/4543d105e55c9230d0ca16f8e56a37b3.jpg");
        $arr = [
            'filename' => "@".CMF_ROOTUP."upload/home/20180912/4543d105e55c9230d0ca16f8e56a37b3.jpg", //图片相对于网站根目录的路径
            'content-type' => 'image/png', //文件类型
            'filelength' => $img_size //图文大小
        ];


        $accessToken = self::getAccessToken($appId,$appSecret);
        $url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=".$accessToken;
        $list =self::getCurl($url,'post','json',$arr);
        return['img'=>$arr,'list'=>$list];

    }









}