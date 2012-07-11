<div class="sysTipOk">
    	<span class="icon icon-ok"></span>
        添加成功！
    </div>
    <div class="regSuccess">
    	<div class="regSuccessTip">
        	<span class="icon icon-att"></span>
            <div class="regTxt">
            	<?php echo $name?>&nbsp;用户的帐号为：<?php echo $username?>，密码为：<?php echo $password?>，<br>您可以在用户管理界面查询。<br>您可以把帐号和密码通知给好友。
            </div>
        </div>
        <div class="regBtn">
        	<button type="button" class="btn btnBlue" onclick='window.location.href="/company/adduser"'>继续添加用户</button>
        	<button type="button" class="btn btnBlue" onclick='window.location.href="/company/listuser"'>返回用户列表</button>
        </div>
    </div>