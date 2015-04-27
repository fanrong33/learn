<?php
header("Content-type: text/html; charset=utf-8");


include_once 'Snoopy.class.php';
$code = 'shunfeng';
$sn   = '909833502189';
$url  = 'http://m.kuaidi100.com/query?type='.$code.'&postid='.$sn;
$snoopy = new Snoopy();
$snoopy->referer = 'http://m.kuaidi100.com/';
$snoopy->fetch($url);
$json_text = $snoopy->results;
dump(json_decode($json_text, true));
/*
array(9) {
  ["nu"] => string(12) "909833502189"
  ["message"] => string(2) "ok"
  ["ischeck"] => string(1) "0"
  ["com"] => string(8) "shunfeng"
  ["updatetime"] => string(19) "2015-04-27 16:12:48"
  ["status"] => string(3) "200"
  ["condition"] => string(2) "00"
  ["data"] => array(5) {
    [0] => array(3) {
      ["time"] => string(19) "2015-04-26 02:47:05"
      ["context"] => string(79) "快件在 【上海集散中心】, 正转运至 【银川兴庆集散中心】"
      ["ftime"] => string(19) "2015-04-26 02:47:05"
    }
    [1] => array(3) {
      ["time"] => string(19) "2015-04-26 02:05:41"
      ["context"] => string(37) "快件到达 【上海集散中心】"
      ["ftime"] => string(19) "2015-04-26 02:05:41"
    }
    [2] => array(3) {
      ["time"] => string(19) "2015-04-25 23:08:11"
      ["context"] => string(79) "快件在 【嘉兴南湖集散中心】, 正转运至 【上海集散中心】"
      ["ftime"] => string(19) "2015-04-25 23:08:11"
    }
    [3] => array(3) {
      ["time"] => string(19) "2015-04-25 21:40:28"
      ["context"] => string(43) "快件到达 【嘉兴南湖集散中心】"
      ["ftime"] => string(19) "2015-04-25 21:40:28"
    }
    [4] => array(3) {
      ["time"] => string(19) "2015-04-25 21:30:43"
      ["context"] => string(28) "顺丰速运 已收取快件"
      ["ftime"] => string(19) "2015-04-25 21:30:43"
    }
  }
  ["state"] => string(1) "0"
}
*/


/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var , $echo =true, $label=null, $strict=true ) {
    $label = ( $label === null ) ? '' : rtrim($label ) . ' ';
    if (! $strict) {
        if (ini_get ('html_errors')) {
            $output = print_r ($var , true);
            $output = '<pre>' . $label . htmlspecialchars($output , ENT_QUOTES) . '</pre>' ;
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var );
        $output = ob_get_clean ();
        if (!extension_loaded ('xdebug')) {
            $output = preg_replace ("/\]\=\>\n(\s+)/m" , '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output , ENT_QUOTES) . '</pre>' ;
        }
    }
    if ( $echo) {
        echo($output );
        return null ;
    }else
        return $output ;
}
?>