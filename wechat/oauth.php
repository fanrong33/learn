<?php
header("Content-type: text/html; charset=utf-8");
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
     * $_SESSION['oauth_time'];
     * $_SESSION['openid'];
     * $_SESSION['oauth_access_token'];
     * $_SESSION['user_info'];
     */
    public function wechatOauth(){
        $scope      = 'snsapi_userinfo'; // 默认静默授权方式，snsapi_userinfo
        $code       = isset($_GET['code']) ? $_GET['code'] : '';
        $oauth_time = isset($_SESSION['oauth_time']) ? $_SESSION['oauth_time'] : 0;

        if(!$code && isset($_SESSION['openid']) && isset($_SESSION['oauth_access_token']) && $oauth_time > time()-3600){
            // 未过期, 使用SESSION中的缓存
            if(!$this->user_info){
                $this->user_info = $_SESSION['user_info'];
            }
            $this->openid = $_SESSION['openid'];
            return $this->openid;
        }else{
            $options = array(
                'token'     => $this->options['token'],
                'appid'     => $this->options['appid'],
                'appsecret' => $this->options['appsecret'],
            );
            $wechat = new Wechat($options);

            if(!$code){
                // 1.获取静默授权的跳转url，得到code
                $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                $oauth_redirect_url = $wechat->getOauthRedirect($url, 'wxbase', $scope);
                header('Location: ' . $oauth_redirect_url);
            }

            if($code){
                // 2.通过code获取access_token
                $json = $wechat->getOauthAccessToken();
                // {access_token,expires_in,refresh_token,openid,scope}
                if(!$json){
                    die('获取用户授权失败，请重新确认');
                }

                $_SESSION['openid']    = $json['openid'];
                $_SESSION['oauth_access_token'] = $json['access_token'];
                $_SESSION['oauth_time'] = time();

                $this->openid = $_SESSION['openid'];
                $user_info = $wechat->getUserInfo($this->openid);
                
                //TODO 涉及普通access_token，所以需要对普通access_token进行缓存
                if($user_info && !empty($user_info['nickname'])){
                    $this->user_info = array(
                        'openid'   => $this->openid,
                        'nickname' => $user_info['nickname'],
                        'sex'      => intval($user_info['sex']),
                        'location' => $user_info['province'].'-'.$user_info['city'],
                        'avatar'   => $user_info['headimgurl'],
                    );
                }elseif( strstr($json['scope'], 'snsapi_userinfo') !== false){
                    // 普通网页授权，需要用户手动同意
                    $user_info = $wechat->getOauthUserinfo($_SESSION['oauth_access_token'], $this->openid);
                    if($user_info && !empty($user_info['nickname'])){
                        $this->user_info = array(
                            'openid'   => $this->openid,
                            'nickname' => $user_info['nickname'],
                            'sex'      => intval($user_info['sex']),
                            'location' => $user_info['province'].'-'.$user_info['city'],
                            'avatar'   => $user_info['headimgurl'],
                        );
                    }else{
                        return $this->open_id;
                    }
                }
                if($this->user_info){
                    $_SESSION['user_info'] = $this->user_error();
                    return $this->openid;
                }
                $scope = 'snsapi_userinfo';
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

?>