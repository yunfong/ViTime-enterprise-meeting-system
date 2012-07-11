<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta name="description" content="" /> 
    <meta name="keywords" content="" /> 
    <title>微泰移动视频会议系统-用户注册</title>  
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
	<script src='/js/lib/jquery.min.js'></script>
	<script src='/js/vitime.js'></script>
	<script src='/js/login.js'></script>
</head>
<body>
	<div class="regTop">
    	<div class="logo">
        	<img src="/images/logo.png" alt="微泰移动视频会议系统"/>
            <span class="logoTxt">微泰移动视频会议系统</span>
        </div>
    </div>
    <div class="regBox">
    	<form name="registerForm" id='registerForm' class="regForm" action='/members/do_register' method='post' onSubmit="return validRegForm(this)">
    	<?php if(!empty($errMsg)):?>
    	<ul>
    		<li>
             	<div class="errorTip">
                 	<span class="icon icon-error"></span><?php echo $errMsg?>
             	</div>
        	</li>
    	</ul>
    	<?php endif;?>
        	<ul>
            	<li>
                	<div class="fname"><span class="redStar">*</span><label>用户名：</label></div>
                    <div class="fvalue"><input type="text" name="username" class="inputStyle" value='<?php echo $username;?>'/></div>
                    <div class="ftip" id='username_ftip'>
                    	<div class="attTip">
                        	请输入您的用户名
                        </div>
                    </div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>密码：</label></div>
                    <div class="fvalue"><input type="password" name="password" class="inputStyle" /></div>
                    <div class="ftip" id='password_ftip'>
                    	<div class="attTip">
                        	请输入您的密码，长度为6-16个字符
                        </div>
                    </div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>确认密码：</label></div>
                    <div class="fvalue"><input type="password" name="repassword" class="inputStyle" /></div>
                    <div class="ftip" id='repassword_ftip'>
                    	<div class="attTip">
                        	请再次输入您的密码，长度为6-16个字符
                        </div>
                    </div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>手机号码：</label></div>
                    <div class="fvalue"><input type="text" name="mobile" class="inputStyle" value='<?php echo $mobile;?>'/></div>
                    <div class="ftip" id='mobile_ftip'>
                    	<div class="attTip">
                        	请输入您的手机号码
                        </div>
                    </div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>邮箱：</label></div>
                    <div class="fvalue"><input type="text" name="email" class="inputStyle" value='<?php echo $email;?>'/></div>
                    <div class="ftip" id='email_ftip'>
                    	<div class="attTip">
                        	请输入您的常用邮箱,如example@examplae.com
                        </div>
                    </div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>公司名称：</label></div>
                    <div class="fvalue"><input type="text" name="company_name" class="inputStyle" value='<?php echo $company_name;?>'/></div>
                    <div class="ftip" id='company_name_ftip'>
                    	<div class="attTip">
                        	请输入企业名称
                        </div>
                    </div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>企业标识：</label></div>
                    <div class="fvalue"><input type="text" name="company_mark" class="inputStyle" value='<?php echo $company_mark;?>'/></div>
                     <div class="ftip" id='company_mark_ftip'>
                    	<div class="attTip">
                        	请输入企业标识
                        </div>
                    </div>
                </li>
                <li class="yzm">
                	<div class="fname"><span class="redStar">*</span><label>验证码：</label></div>
                    <div class="fvalue">
                        <table width="100%" border="0">
                          <tr>
                            <td><input type="text" name="verifycode" class="inputsm" /></td>
                            <td><img src="/captcha.php" alt="看不清？换一换" id='verifyCode' onclick='changeVerify()'/></td>
                            <td><span class="icon icon-rf"></span><a href="javascript:void(changeVerify());" onclick='changeVerify()'>看不清？换一换</a></td>
                          </tr>
                        </table>
                    </div>
                    <div class="ftip vhide" id='verifycode_ftip'>
                    	<div class="errorTip">
                        	<span class="icon icon-error"></span>请输入验证码
                        </div>
                    </div>
                </li>
                <li>
                	<div class="fname"></div>
                    <div class="fvalue" style="line-height:17px;"><input type='checkbox' name='view_agreement' checked="checked" /><a href="/user_agreement.html" target='_blank'>&nbsp;我已阅读并介绍《企业移动视频会议协议》</a></div>
                    <div class="ftip"></div>
                </li>
                <li>
                	<div class="fname"></div>
                    <div class="fvalue"><input type="submit" class="btn btnBlue" value='注 册'></input><button type="reset" class="btn btnBlue" onclick='window.location.href="/"'>取消</button></div>
                    <div class="ftip"></div>
                </li>
            </ul>
        </form>
    </div>
    <div class='reg_footer'>
    Copyright 2010-2012 © 福州微泰网络科技有限公司 版权所有 闽ICP备11000518号
    </div>
</body>
</html>