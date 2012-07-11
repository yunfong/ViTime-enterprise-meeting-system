<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content=""> 
    <meta name="keywords" content=""> 
    <title>微泰移动视频会议系统-首页</title>  
	<link rel="stylesheet" type="text/css" href="css/style.css" />
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript">
		$(document).ready(function(){
		//图片自动切换
		var totWidth=0;
		var positions = new Array();
		$('#slides .slide').each(function(i){
			positions[i]= totWidth;
			totWidth += $(this).width();
			if(!$(this).width())
			{
				return false;
			}
		});
		$('#slides').width(totWidth);
		$('#tab ul li a').mouseover(function(e,keepScroll){
				$("#zt-banner").removeClass("tab" + pos);
				$('li.menuItem').removeClass('selected')
				$(this).parent().addClass('selected');
				var pos = $(this).parent().prevAll('.menuItem').length;
				switch(pos){
					case 0:
						$("#zt-banner").attr("class","tab0");
						break;
					case 1:
						$("#zt-banner").attr("class","tab1");
						break;
					case 2:
						$("#zt-banner").attr("class","tab2");
						break;
				}
				
				$('#slides').stop().animate({marginLeft:-positions[pos]+'px'},400);
				e.preventDefault();
				// 停止
				if(!keepScroll) clearInterval(itvl);
		});
		$('#tab ul li.menuItem:first').addClass('selected').siblings();
		var current=1;
		function autoAdvance()
		{
			if(current==-1) return false;
			$('#tab ul li a').eq(current%$('#tab ul li a').length).trigger('mouseover',[true]);
			current++;
		}
		var changeEvery = 5;
		var itvl = setInterval(function(){autoAdvance()},changeEvery*1000);
		//end图片自动切换
		});
	</script>
    <style type="text/css">
		.loginTop{ }
		.loginTop .logo{ padding-top:70px;}
		.btnContent{ width:367px; height:256px; margin:0 auto; padding-top:70px;}
		.btnContent a{text-decoration:none; color:#fff; text-align:center; line-height:42px; font-size:20px;}
		.btnContent .mgr{ margin-right:55px;}
		.btnContent .btnline{ margin:10px 0px;}
	</style>
</head>
<body>
	<div class="loginTop">
    	<div id="zt-banner" class="tab0">
        	<div class="tabSidesBox">
            	<div id="slides" class="slideBox">
                    <div class="slide">
                        <a href=""> 
                            <img src="images/pic01.jpg" width="1144" height="322"/>
                        </a> 
                    </div>
                    <div class="slide">
                        <a  href=""> 
                            <img src="images/pic02.jpg" width="1144" height="322"/>
                        </a> 
                    </div>
                    <div class="slide">
                        <a href=""> 
                            <img src="images/pic03.jpg" width="1144" height="322"/>
                        </a> 
                    </div>
                </div>
                <div id="tab" class="tab">
                    <ul>
                        <li class="menuItem">
                            <a href="">
                                
                            </a>
                        </li>
                        <li class="menuItem">
                            <a href="">
                                
                            </a>
                        </li>
                        <li class="menuItem last">
                            <a href="">
                                
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
		</div>
        <div class="logo">
        	<img src="images/logo.png" alt="微泰移动视频会议系统"/>
            <span class="logoTxt">微泰移动视频会议系统</span>
        </div>
    </div>
    <div class="btnContent">
    	<div class="btnline">
        	<a href="/members/admin_login?from=index&timestamp=<?php echo time()?>"  class="btn btnBlue mgr">管理员登录</a>
        	<a href="/members/login"   class="btn btnBlue">普通用户登录</a>
        </div>
       <div class="btnline">
        	<a href="/members/register"    class="btn btnGreenBig">注 册</a>
        </div>
        <div class="btnline">
        	<a href="/tourist" class="btn btnBlueBig">进入公共会议室</a>
        </div>
    </div>
        <div class='reg_footer'>
    Copyright 2010-2012 © 福州微泰网络科技有限公司 版权所有 闽ICP备11000518号
    </div>
</body>
</html>