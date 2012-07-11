<?php $this->load->view('/mymeeting/cmp_user_nav.php')?>
<?php $this->load->view('/mymeeting/profile_menu.php')?>
<div class="regBox">
    	<form id="updateuserForm" name="updateuserForm" class="regForm" method='post' action='/mymeeting/do_change_password' onsubmit='return validateCPWDForm(this)'>
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
                	<div class="fname"><span class="redStar">*</span><label>姓名：</label></div>
                    <div class="fvalue"><?php echo $login_user->name?></div>
                    <div class="ftip"></div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>账号：</label></div>
                    <div class="fvalue"><?php echo $login_user->username?></div>
                    <div class="ftip"></div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>原密码：</label></div>
                    <div class="fvalue"><input type="password" name="password" value='' class="inputStyle"/></div>
                    <div class="ftip">
                    	<div class="attTip">
                        	填写旧密码
                        </div>
                    </div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>新密码：</label></div>
                    <div class="fvalue"><input type="password" name="newpassword" value='' class="inputStyle"/></div>
                    <div class="ftip">
                    	<div class="attTip">
                        	填写新的密码
                        </div>
                    </div>
                </li>
                
                 <li>
                	<div class="fname"><span class="redStar">*</span><label>确认新密码：</label></div>
                    <div class="fvalue"><input type="password" name="renewpassword" value='' class="inputStyle"/></div>
                    <div class="ftip">
                    	<div class="attTip">
                        	重新输入新的密码
                        </div>
                    </div>
                </li>
               
                <li>
                	<div class="fname"></div>
                    <div class="fvalue">
                    	<input type="submit" class="btn btnBlue" value='修改'></input>
                    	<input type="reset" class="btn btnBlue" value='取消' onclick='this.form.reset();' style="margin-left:20px;"></input>
                    </div>
                    <div class="ftip"></div>
                </li>
            </ul>
        </form>
    </div>