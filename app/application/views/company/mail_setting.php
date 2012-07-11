<?php $this->load->view('/company/cmp_admin_nav.php')?>
<?php $this->load->view('/company/profile_menu.php')?>
<div class="regBox">
    	<form id="updateuserForm" name="updateuserForm" class="regForm" method='post' action='/company/mail_setting' onsubmit='return updateEmailSetting(this);'>
    	<input type='hidden' name='user_id' value='<?php echo $id ?>' />
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
                	<div class="fname"><span class="redStar">*</span><label>账号：</label></div>
                    <div class="fvalue"><?php echo $login_user->username?></div>
                    <div class="ftip"></div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>邮箱帐号：</label></div>
                    <div class="fvalue"><input type="text" name="email" value='<?php echo $email?>' class="inputStyle" onblur='getSmtp(this)'/></div>
                    <div class="ftip">
                    	<div class="attTip">
                        	填写用于发送邮件给其他人的邮箱帐号，如：username@163.com
                        </div>
                    </div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>密码：</label></div>
                    <div class="fvalue"><input type="text" name="password" value='<?php echo $password?>' class="inputStyle"/></div>
                    <div class="ftip">
                    	<div class="attTip">
                        	填写邮箱帐号的密码
                        </div>
                    </div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>SMTP地址：</label></div>
                    <div class="fvalue"><input type="text" name="smtp" value='<?php echo $smtp?>' class="inputStyle"/></div>
                    <div class="ftip">
                    	<div class="attTip">
                        	填写邮箱服务器的SMTP地址，如：smtp.163.com
                        </div>
                    </div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>SMTP端口：</label></div>
                    <div class="fvalue"><input type="text" name="port" value='<?php echo ($port?$port:'25')?>' class="inputStyle"/></div>
                    <div class="ftip">
                    	<div class="attTip">
                        	填写邮箱服务器的SMTP地址端口号，默认25
                        </div>
                    </div>
                </li>
                <li>
                	<div class="fname"></div>
                    <div class="fvalue">
                    	<input type="submit" class="btn btnBlue" value='确定'></input>
                    	<input type="reset" class="btn btnBlue" value='取 消' onclick='window.history.back();' style="margin-left:20px;"></input>
                    </div>
                    <div class="ftip"></div>
                </li>
            </ul>
        </form>
    </div>
    <script>
    function updateEmailSetting(form){
        
    }

    function getSmtp(email){
        var emailV = email.value;
        var form = email.form;
        var smtpHost = emailV.split('@');
        if(smtpHost.length>1){
        	smtpHost = smtpHost[1];
        	switch(smtpHost){
        	case 'vip.qq.com':
            	smtpHost = 'qq.com';
            	break;
        	case 'google.com':
            	smtpHost = 'gmail.com';
            	break;
        	}
        	
        	form.smtp.value = 'smtp.' + smtpHost;
        	form.port.value = 25;
        }
    }
    </script>