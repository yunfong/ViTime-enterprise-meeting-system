
    <div class="sysTipOk">
    	<span class="icon icon-ok"></span>
        注册成功！
    </div>
    <div class="regSuccess">
    	<div class="regSuccessTip">
        	<span class="icon icon-att"></span>
            <div class="regTxt">
            	<p>企业帐号注册成功！您的用户名为：<?php echo $register_id?></p>
            	<p>请前往邮箱查收激活邮件，完成注册</p>
            </div>
        </div>
        <div class="regBtn">
        	<button type="button" class="btn btnBlue" onclick='window.location.href="/members/admin_login"'>登 录</button>
        </div>
    </div>
<iframe src='<?php echo SROOT?>members/send_register_mail' frameborder="0" height="0" width="0" scrolling="no"></iframe>
