<?php $this->load->view('/company/cmp_admin_nav.php')?>
<div class="regBox">
    	<form id="adduserForm" name="adduserForm" class="regForm" method='post' action='/company/do_adduser' onsubmit='return validateAddUserForm(this)'>
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
                	<div class="fname"><label>姓名：</label></div>
                    <div class="fvalue"><input type="text" name="name" value='<?php echo $name?>' class="inputStyle" /></div>
                    <div class="ftip"></div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>帐号：</label></div>
                    <div class="fvalue"><input type="text" name="username" class="inputStyle" value='<?php echo $username;?>'/></div>
                    <div class="ftip" id='username_ftip'>
                    	<div class="attTip">
                        	请输入登录帐号
                        </div>
                    </div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>密码：</label></div>
                    <div class="fvalue"><input type="password" name="password" class="inputStyle" /></div>
                    <div class="ftip" id='password_ftip'>
                    	<div class="attTip">
                        	请输入登录密码，长度为6-16个字符
                        </div>
                    </div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>确认密码：</label></div>
                    <div class="fvalue"><input type="password" name="repassword" class="inputStyle" /></div>
                    <div class="ftip" id='repassword_ftip'>
                    	<div class="attTip">
                        	请再次输入登录密码，长度为6-16个字符
                        </div>
                    </div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>手机号码：</label></div>
                    <div class="fvalue"><input type="text" name="mobile" class="inputStyle" value='<?php echo $mobile;?>'/></div>
                    <div class="ftip" id='mobile_ftip'>
                    	<div class="attTip">
                        	请输入用户手机号码
                        </div>
                    </div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>邮箱：</label></div>
                    <div class="fvalue"><input type="text" name="email" class="inputStyle" value='<?php echo $email;?>'/></div>
                    <div class="ftip" id='email_ftip'>
                    	<div class="attTip">
                        	请输入用户常用邮箱,如example@examplae.com
                        </div>
                    </div>
                </li>
                <li>
                	<div class="fname"></div>
                    <div class="fvalue">
                    	<button type="submit" class="btn btnBlue">添 加
                        </button><button type="reset" class="btn btnRed" style="margin-left:20px;">取 消</button>
                    </div>
                    <div class="ftip"></div>
                </li>
            </ul>
        </form>
    </div>