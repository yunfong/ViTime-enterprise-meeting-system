    <div class="loginBox">
    	<form id="loginForm" name="loginForm" class="loginForm validform " method='post' action='/members/get_password'>
        	<ul>
                <li>
                	<div class="errorTip">
                     	<span class="icon"></span>找回密码，请输入您注册时的帐号和邮箱
                    </div>
                </li>
            	<li>
                	<label>用户名：</label>
                    <input type="text" name="username" class="inputStyle" value='<?php echo $username?>'/>
                </li>
                <li>
                	<label>邮箱：</label>
                    <input type="text" name="email" class="inputStyle"  value='<?php echo $email?>'/>
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
				<button type="submit" class="btn btnBlue">取回密码</button>
				</div>
				</li>
            </ul>
        </form>
    </div>