<?php
define('APP_NAME', 'wechat');
header("Content-type: text/html; charset=utf-8");
include_once 'wechat.class.php';
include_once 'errCode.php';
include_once 'EasyWechat.class.php';
include_once 'Log.class.php';

$options = array(
    'appid'          =>'wxd9b8d1a341796cc7',	//填写高级调用功能的appid
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

// 1.获取静默授权的跳转url，得到code
$redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].'/wxauth/authorize.php';
$oauth_redirect_url = $wechat->getOauthRedirect($redirect_uri, '', 'snsapi_userinfo');
Log::info('oauth_redirect_url: '.$oauth_redirect_url);

header("Location: " . $oauth_redirect_url);

?>