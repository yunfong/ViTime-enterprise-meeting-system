<?php if($is_login):?>
    <div class="nav">
    	<ul>
        	<li><a href="/admin/listcompany" class="<?php echo ($_action=='listcompany' || $_action=='index'?'selected':'');?>">企业用户管理</a></li>
            <li><a href="/admin/change_password" class="<?php echo ($_action=='change_password'?'selected':'');?>">修改密码</a></li>
<!--            <li><a href="/company/deluser" class="<?php echo ($_action=='deluser'?'selected':'');?>">删除</a></li>-->
        </ul>
    </div>
<?php endif;?>