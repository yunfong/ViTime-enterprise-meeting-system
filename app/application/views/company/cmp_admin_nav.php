<?php if($is_login):?>
    <div class="nav">
    	<ul>
	    	<li><a href="/company/company_meeting" class="<?php echo ($_action=='company_meeting'?'selected':'');?>">企业会议室</a></li>
            <li><a href="/company/public_meeting" class="<?php echo ($_action=='public_meeting'?'selected':'');?>">公共会议室</a></li>
        	<li><a href="/company/listuser" class="<?php echo ($_action=='listuser' || $_action=='index'?'selected':'');?>">用户管理</a></li>
            <li><a href="/company/adduser" class="<?php echo ($_action=='adduser'?'selected':'');?>">添加用户</a></li>
			
        </ul>
    </div>
<?php endif;?>
    <link rel="stylesheet" type="text/css" href="/js/lib/css/ui-lightness/jquery-ui-1.8.19.custom.css" />
    <script src='/js/lib/jquery-ui-1.8.19.custom.min.js'></script>
    <script src='/js/lib/jquery.ui.datepicker-zh-CN.js'></script>
    <script src='/js/company.js'></script>