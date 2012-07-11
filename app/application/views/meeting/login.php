    <div class="loginBox">
    	<form id="loginForm" name="loginForm" class="loginForm validform " method='post' action='/members/do_login' onsubmit='return m_login(this)'>
    	<input type='hidden' name='to_url' value='<?php echo $to_url?>' />
        	<ul>
        		<li>
                	参与会议需要先登录，请输入登录信息：
                </li>
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
				<span id="loading"></span>
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
    function m_login(form){
        var data = {};
        var els = form.elements;
        for(var i=0;i<els.length;i++){
            var it = els[i];
            if($.trim(it.value) == "" && it.tagName == 'TEXT'){
                alert('请填写完整再登录');
                return false; 
            }
            data[it.name] = it.value;
        }
		$('#loading').html("正在登录...");
		$.post('/members/ajax_login',data,function(res){
			eval('res='+res);
			if(res.errMsg){
				alert(res.errMsg);
			}
			if(res.success && res.success == true){
				window.location.href = form.to_url.value;
			}
			$('#loading').html("");
		});
        return false;
    }
    </script>