<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>微泰移动视频会议系统-<?php echo $title?></title>
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
	<script>var _controller = "<?php echo strtolower($_controller)?>",_action="<?php echo $_action?>";var USER_MONTH_PAY=<?php echo USER_MONTH_PAY?>;var USER_PUB_PAY=<?php echo USER_PUB_PAY?>;var isOnTry=<?php echo CmpAdminManage::getInstance()->isOnTry()?'true':'false'?></script>
	<script src='/js/lib/jquery.min.js'></script>
	<script src='/js/vitime.js'></script>

</head>
<body>
	<div class="regTop">
    	<div class="logo">
        	<img src="/images/logo.png" alt="微泰移动视频会议系统"/>
            <span class="logoTxt">微泰移动视频会议系统</span>
        </div>
        <?php if($is_login):?>
        <div class="userBox">
        	<div class="userInfo">欢迎回来：<?php echo $login_user->username?>&nbsp;，账户余额：￥<?php echo $login_user->v_money?></div>
            <div class="userBtn">
            	<!--<a class="btn btnExit" href="/" style="margin-right:10px;">首页</a>--><a href="/<?php echo strtolower($_controller)?>/profile" class="btn btnAc">帐号</a><a href="/<?php echo strtolower($_controller)?>/recharge" class="btn btnBuy">充值</a><a href="/members/logout" class="btn btnExit">退出</a>
            </div>
        </div>
        <?php else:?>
        <div class="userBox">
        <div class="userInfo">&nbsp;</div>
            <div class="userBtn">
            	<a href="/" class="btn btnExit">首页</a>
            </div>
        </div>
        <?php endif;?>
    </div>
