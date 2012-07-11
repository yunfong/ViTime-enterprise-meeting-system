<?php $this->load->view('/admin/nav.php')?>
<?php $this->load->view('/admin/profile_menu.php')?>
<div class="regBox">
    	<form id="updateuserForm" name="updateuserForm" class="regForm" method='post' action='/admin/profile'>
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
                	<div class="fname"><label>账号：</label></div>
                    <div class="fvalue"><input type="text" name='username' value="<?php echo $username?>" class="inputStyle" readonly="readonly" style='color:gray;' /></div>
                    <div class="ftip">
                    	<div class="attTip">
                        	帐号不能修改！！
                        </div>
                    </div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>手机号码：</label></div>
                    <div class="fvalue"><input type="text" name="mobile" value='<?php echo $mobile?>' class="inputStyle"/></div>
                    <div class="ftip">
                    	
                    </div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>邮箱：</label></div>
                    <div class="fvalue"><input type="text" name="email" value='<?php echo $email?>' class="inputStyle"/></div>
                    <div class="ftip" style='display:none;'>
                    	<div class="attTip">
                        	请输入您的常用邮箱,如example@examplae.com
                        </div>
                    </div>
                </li>
                 
                <li>
                	<div class="fname"></div>
                    <div class="fvalue">
                    	<input type="submit" class="btn btnBlue" value='更新'></input>
                    	<input type="reset" class="btn btnBlue" value='取 消' onclick='window.history.back();' style="margin-left:20px;"></input>
                    </div>
                    <div class="ftip"></div>
                </li>
            </ul>
        </form>
    </div>