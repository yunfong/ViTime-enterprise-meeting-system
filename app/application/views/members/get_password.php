    <div class="loginBox">
    	<form id="loginForm" name="loginForm" class="loginForm validform " method='post' action='/members/get_password' onsubmit=" return validate(this)">
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
				<button type="button" class="btn btnRed" onclick="window.location.href='/'">取消</button>
				</div>
				</li>
                <li>
                <div style='height:50px;'>
                &nbsp;
				</div>
                </li>
            </ul>
        </form>
    </div>
    <script>
    function validate(form){
        if(form.username.value == ''){
            alert('用户名不能为空');
            return false;
         }

        if(form.email.value == ''){
            alert('邮箱不能为空');
            return false;
         }

        jNotify("正在提交...",{ShowOverlay:true});
        return true;
    }
    </script>