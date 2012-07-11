    <div class="loginBox">
    	<form id="loginForm" name="loginForm" class="loginForm validform " method='post' action='/members/do_login'>
        	<ul>
            	<li>
                	<label>用户名：</label>
                    <input type="text" name="username" class="inputStyle" value='<?php echo $username?>'/>
                </li>
                <li>
                	<label>密码：</label>
                    <input type="password" name="password" class="inputStyle"  value=''/>
                </li>
                <li>
                	<label>公司标识：</label>
                    <input type="text" name="company_mark" class="inputStyle"  value='<?php echo $company_mark?>'/>
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
				<button type="submit" class="btn btnBlue">登 录</button>
				<button type="reset" class="btn btnBlue" onclick="window.location.href='/tourist'">进入公共会议</button>
				</div>
				</li>
                <li>
                	<input type="checkbox" class="mg-r10" name='keepme' value='1'/>记住我
                	<span class="icon icon-fpwd"></span><a href="/members/get_password">忘记密码 ?</a>
                	<span class="icon icon-freg"></span><a href="/members/register">还未注册 ?</a>
                	<span class="icon ">&nbsp;&nbsp;&nbsp;&nbsp;</span><a href="/">返回首页</a>
                </li>
                <li>
                <div style='height:50px;'>
                &nbsp;
				</div>
                </li>
            </ul>
        </form>
    </div>