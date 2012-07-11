    <div class="loginBox">
    	<form id="loginForm" name="loginForm" class="loginForm validform " method='post' action='/members/set_password'>
        	<ul>
                <li>
                	<div class="errorTip">
                     	<span class="icon"></span>重置密码，请输入您的新密码
                    </div>
                </li>
            	<li>
                	<label>新密码：</label>
                    <input type="password" name="newpassword" class="inputStyle" value='<?php echo $newpassword?>'/>
                </li>
                <li>
                	<label>确认新密码：</label>
                    <input type="password" name="newpassword2" class="inputStyle"  value='<?php echo $newpassword2?>'/>
                </li>
                <?php if(!empty($errMsg)):?>
                <li>
                	<div class="errorTip">
                     	<span class="icon icon-error"></span><?php echo $errMsg?>
                    </div>
                </li>
                <?php endif;?>
				<li>
				<div class="fvalue">
                <input type="hidden" name="code" value="<?php echo $code?>" />
                <input type="hidden" name="mid" value="<?php echo $mid?>" />
				<button type="submit" class="btn btnBlue">确 定</button>
				</div>
				</li>
            </ul>
        </form>
    </div>