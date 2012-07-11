
    <div class="sysTipOk">
    <?php if(!empty($id)):?>
    	<span class="icon icon-ok"></span>
        激活成功！
    <?php else:?>
        <span class="icon icon-error"></span>
      激活失败！
<?php endif;?>        
    </div>
    <div class="regSuccess">
    	<div class="regSuccessTip">
        	<span class="icon icon-att"></span>
            <div class="regTxt">
            <?php if(!empty($id)):?>
            	<p>尊敬的<?php echo $username?>,您好，您已经顺利完成微泰移动视频会议系统产品注册流程，并且激活这个账号。
      以下是您的注册信息：</p>
                       <p>帐号：<?php echo $username?><br />公司：<?php echo $company_name?><br />公司标识：<?php echo $company_mark?><br />邮箱：<?php echo $email?>
</p>
<?php else:?>
<p>激活失败！</p>
<?php endif;?>
            </div>
        </div>
        <div class="regBtn">
        	<button type="button" class="btn btnBlue" onclick='window.location.href="/members/admin_login"'>登 录</button>
        </div>
    </div>
<iframe src='<?php echo SROOT?>members/send_register_mail' frameborder="0" height="0" width="0" scrolling="no"></iframe>
