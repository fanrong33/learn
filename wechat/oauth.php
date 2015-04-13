<?php
/**
 * 微信OAuth认证示例
 */

$options = array(
    'appid'          =>'wxd9b8d1a341796cc7',    //填写高级调用功能的appid
    'appsecret'      =>'975405c44b2eef25ea92c0c4490d40ff', //填写高级调用功能的密钥
    'token'          =>'auB3PSVDSjkU', //填写你设定的key
    'encodingaeskey' =>'mt6jz7Xdkei9rk5WyFTYS4xN94KJrH338DNoshAjEZ6', //填写加密用的EncodingAESKey，如接口为明文模式可忽略
);
$wechat_oauth = new WechatOAuth($options);
var_dump($wechat_oauth->getUserInfo());
var_dump($wechat_oauth->getOpenid());


include_once 'wechat.class.php';
/**
 * 面向对象编程思想
 * 
 */
class WechatOAuth {

    private $options;
    private $openid;
    private $user_info;

    public function __construct($options){
        $this->options = $options;
        $this->wechatOauth();

        if(!isset($_SESSION)){
            session_start();
        }
    }

    /**
     * 微信网页授权进程
     * $_SESSION['token_time'];
     * $_SESSION['openid'];
     * $_SESSION['oauth_token'];
     * $_SESSION['user_info'];
     */
    private function wechatOauth(){
        $scope      = 'snsapi_base'; // 默认静默授权方式，snsapi_userinfo
        $code       = isset($_GET['code']) ? $_GET['code'] : '';
        $token_time = isset($_SESSION['token_time']) ? $_SESSION['token_time'] : 0;

        if(!$code && isset($_SESSION['openid']) && isset($_SESSION['oauth_token']) && $token_time > time()-3600){
            // 未过期, 使用SESSION中的缓存
            if(!$this->user_info){
                $this->user_info = $_SESSION['user_info'];
            }
            $this->openid = $_SESSION['openid'];
            return $this->openid;
        }else{
            // 通过code获取Access Token
            $options = array(
                'token'     => $this->options['token'],
                'appid'     => $this->options['appid'],
                'appsecret' => $this->options['appsecret'],
            );
            $wechat = new Wechat($options);

            if(!$code){
                // 1.获取静默授权的跳转url，得到code
                if($scope == 'snsapi_base'){
                    $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                    $_SESSION['wx_redirect'] = $url;
                }else{
                    $url = $_SESSION['wx_redirect'];
                }
            }
        }












    }

    public function getUserInfo(){
        return $this->user_info;
    }

    public function getOpenid(){
        return $this->openid;
    }

}

?>