<?php if(strpos(@$_action,'success') !== false):?>
<script src='/js/ZeroClipboard.js'></script>
<?php endif;?>
<link rel="stylesheet" type="text/css" href="/js/lib/jNotify/jNotify.jquery.css" />
<script src='/js/lib/jNotify/jNotify.jquery.js'></script>
<?php if($is_login || strtolower($_controller) == 'tourist'):?>
<div class="footer">
    <p><a href="http://meeting.vitime.cn/a/contact.html" target='_blank'>联系我们</a> <b>|</b> <a href="http://meeting.vitime.cn/a/private.html"  target='_blank'>隐私政策</a> <b>|</b> <a href="http://meeting.vitime.cn/a/rencaizhaopin/"  target='_blank'> 人才招聘</a> <b>|</b> Copyright 2010-2012 © 福州微泰网络科技有限公司 版权所有 闽ICP备11000518号 </p>
  </div>
  <?php else:?>
      <div class='reg_footer'>
    Copyright 2010-2012 © 福州微泰网络科技有限公司 版权所有 闽ICP备11000518号
    </div>
  <?php endif;?>
</body>
</html>