<div id='profile_menu_panle'>  
  <ul id="profile_menu">  
    <li><a href="/<?php echo strtolower($_controller)?>/profile" <?php echo ($_action == 'profile'?"class='profile_menu_selected_bg'":'')?>>个人资料</a></li>  
    <li><a href="/<?php echo strtolower($_controller)?>/change_password" <?php echo ($_action == 'change_password'?"class='profile_menu_selected_bg'":'')?>>修改密码</a> </li>
  </ul>  
</div>  