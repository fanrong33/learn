<?php
define('APP_NAME', 'wechat');
header("Content-type: text/html; charset=utf-8");
include_once 'wechat.class.php';
include_once 'errCode.php';
include_once 'EasyWechat.class.php';
include_once 'Log.class.php';

Log::info('code: '.$_GET['code']);

$options = array(
    'appid'          =>'wxd9b8d1a341796cc7',    //填写高级调用功能的appid
    'appsecret'      =>'975405c44b2eef25ea92c0c4490d40ff', //填写高级调用功能的密钥
    'token'          =>'auB3PSVDSjkU', //填写你设定的key
    'encodingaeskey' =>'mt6jz7Xdkei9rk5WyFTYS4xN94KJrH338DNoshAjEZ6', //填写加密用的EncodingAESKey，如接口为明文模式可忽略
    'debug'          => true,
    'cachedir'       =>'./cache/', //填写缓存目录，默认为当前运行目录的子目录cache下
    'logfile'        =>'run.log' //填写日志输出文件，可选项。如果没有提供logcallback回调，且设置了输出文件则将日志输出至此文件，如果省略则不输出
);
$wechat = new EasyWechat($options);

// $access_token = ''; // 从远程接口获取
$access_token = $wechat->checkAuth();
Log::info('access_token: '.$access_token);

if (!$access_token) {
    echo "获取access_token失败！<br/>";
    echo '错误码：'.$wechat->errCode.'<br/>';
    echo '错误原因：'.ErrCode::getErrText($wechat->errCode);
    exit;
}

// 通过code获取Access Token
$oauth_access_token = $wechat->getOauthAccessToken();
Log::info('oauth_access_token: ' . json_encode($oauth_access_token));

var_dump($oauth_access_token);

$user_info = $wechat->getOauthUserinfo($oauth_access_token['access_token'], $oauth_access_token['openid']);
var_dump($user_info);
if(!$user_info){
    echo "获取授权后的用户资料失败！<br/>";
    echo '错误码：'.$wechat->errCode.'<br/>';
    echo '错误原因：'.ErrCode::getErrText($wechat->errCode);
}

?>