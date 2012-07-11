<div id='profile_menu_panle'>  
  <ul id="profile_menu">  
    <li><a href="/<?php echo strtolower($_controller)?>/profile" <?php echo ($_action == 'profile'?"class='profile_menu_selected_bg'":'')?>>个人资料</a></li>  
    <li><a href="/<?php echo strtolower($_controller)?>/meeting_record" <?php echo ($_action == 'meeting_record'?"class='profile_menu_selected_bg'":'')?>>会议记录</a></li>
    <li><a href="/<?php echo strtolower($_controller)?>/change_password" <?php echo ($_action == 'change_password'?"class='profile_menu_selected_bg'":'')?>>修改密码</a> </li>
    <li><a href="/<?php echo strtolower($_controller)?>/mail_setting" <?php echo ($_action == 'mail_setting'?"class='profile_menu_selected_bg'":'')?>>邮箱帐号设置</a></li>
    <li><a href="/<?php echo strtolower($_controller)?>/recharge_history" <?php echo ($_action == 'recharge_history'?"class='profile_menu_selected_bg'":'')?>>充值记录</a></li>
    <li><a href="/<?php echo strtolower($_controller)?>/expense_history" <?php echo ($_action == 'expense_history'?"class='profile_menu_selected_bg'":'')?>>消费记录</a></li>
  </ul>  
</div>  