
// 扩展API准备完成后要执行的操作
function plusReady(){
	// 隐藏滚动条
	plus.webview.currentWebview().setStyle({scrollIndicator:'none'});
	
}

if(window.plus){
    plusReady();
}else{ 
    document.addEventListener("plusready", plusReady, false);
}

// DOMContentLoaded事件处理
var is_domready=false;
document.addEventListener('DOMContentLoaded',function(){
	is_domready=true;
	
	document.getElementById('J_back').addEventListener('tap', function(){
		back(true);
	});
	
	g_init();
},false);

// 处理返回事件
function back(hide){
	if(window.plus){
		var ws = plus.webview.currentWebview();
		if(hide){
			console.info(ws.id+' hide');
			ws.hide('auto', 300);
		}else{
			console.info(ws.id+' close');
			ws.close('auto', 300);
		}
	}else if(history.length>1){
		history.back();
	}else{
		window.close();
	}
}

// 通用元素对象
var _dout_;
var _dcontent_;
function g_init(){
	_dout_=document.getElementById("output");
	_dcontent_=document.getElementById("dcontent");
}

// 清空输出内容
function out_clean(){
	_dout_.innerHTML="";
	_dout_.scrollTop=0;//在iOS8存在不滚动的现象
};
// 输出内容
function out_set(s){
	_dout_.innerHTML=s+"<br/>";
	(0==_dout_.scrollTop)&&(_dout_.scrollTop=1);//在iOS8存在不滚动的现象
};

// 输出行内容
function out_line(s){
	_dout_.innerHTML+=s+"<br/>";
	(0==_dout_.scrollTop)&&(_dout_.scrollTop=1);//在iOS8存在不滚动的现象
};


// 格式化时长字符串，格式为"HH:MM:SS"
function timeToStr(ts){
	if(isNaN(ts)){
		return "--:--:--";
	}
	var h=parseInt(ts/3600);
	var m=parseInt((ts%3600)/60);
	var s=parseInt(ts%60);
	return (ultZeroize(h)+":"+ultZeroize(m)+":"+ultZeroize(s));
};

// 格式化日期时间字符串，格式为"YYYY-MM-DD HH:MM:SS"
function dateToStr(d){
	return (d.getFullYear()+"-"+ultZeroize(d.getMonth()+1)+"-"+ultZeroize(d.getDate())+" "+ultZeroize(d.getHours())+":"+ultZeroize(d.getMinutes())+":"+ultZeroize(d.getSeconds()));
};

/**
 * zeroize value with length(default is 2).
 * @param {Object} v
 * @param {Number} l
 * @return {String} 
 */
function ultZeroize(v,l){
	var z="";
	l=l||2;
	v=String(v);
	for(var i=0;i<l-v.length;i++){
		z+="0";
	}
	return z+v;
};


/**
 * 始终开启回弹效果
 * 当容器内容高度不足以启动（-webkit-overflow-scrolling:touch）时，自动在后面添加空白div
 * @param  string   wrapper_id 容器id, 设置了回弹样式
 * @param  string   inner_id   容器内标签id
 */
function always_bounce(wrapper_id, inner_id){
    var wrapper_el = document.getElementById(wrapper_id);
    var inner_el   = document.getElementById(inner_id);

    var wrapper_height = outer_height(wrapper_el, true);
    var inner_height   = outer_height(inner_el, true);
		
    if(inner_height <= wrapper_height){
        // 在滚动条的里面添加空的元素，撑开内容
        var el = document.createElement('div');
        el.style.height = (wrapper_height-inner_height+1)+'px';
        wrapper_el.appendChild(el);
    }
	
    // 原生方法
    function outer_height(el, includeMargin){
      var height = el.offsetHeight;
     
      if(includeMargin){
        var style = el.currentStyle || getComputedStyle(el);
        height += parseInt(style.marginTop) + parseInt(style.marginBottom);
      }
      return height;
    }
}