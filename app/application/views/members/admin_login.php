    <div class="loginBox">
    	<form id="loginForm" name="loginForm" class="loginForm validform " method='post' action='/members/do_admin_login'>
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
                <li>
                	<input type="radio" class="mg-r10" checked name='user_type' value='company' />企业管理员 &nbsp;&nbsp;<input type="radio" class="mg-r10" name='user_type' value='sys'/>系统管理员
                </li>
                <li>
                <?php if(!empty($errMsg)):?>
                	<div class="errorTip">
                     	<span class="icon icon-error"></span><?php echo $errMsg?>
                    </div>
                <?php endif;?>
                </li>
                
                <li>
				<div class="fvalue">
				<button type="submit" class="btn btnBlue" onclick='admin_login();return false;'>登 录</button>
				<button type="button" class="btn btnRed" onclick="window.history.back()">取消</button>
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