<?php $this->load->view('/company/cmp_admin_nav.php')?>
<div class="sysTipOk">
    	<span class="icon icon-ok"></span>
        预约成功！
    </div>
    <div class="regSuccess">
    	<div class="regSuccessTip">
        	<span class="icon icon-att"></span>
            <div class="regTxt">
            	会议主题：<?php echo $title?><br>会议密码：<?php echo $password?><br>会议室人数：<?php echo $usercount?>人
            </div>
        </div>
    </div>
    <div class="regBot">
    	<div class="col">
            <h3>您可以通过以下方式通知会议的参会者：</h3>
            <div class="colBox">
                <table border="0">
                  <tr>
                    <td class="td1"><button type="button" class="btn btnBlue" onclick='to_send_public_mail()'>发送邮件</button></td>
                    <td class="td2">使用电脑默认邮件客户端发送会议邀请给会议的参与者。</td>
                  </tr>
                  <tr>
                    <td class="td1"><button type="button" class="btn btnBlue" id='copy_button' >复制会议信息</button></td>
                    <td class="td2">复制会议的详细信息，使用聊天工具或者网页邮箱发送给与会者。</td>
                  </tr>
                </table>
            </div>
        </div>
    </div>
    <textarea id='meeting-content' style='display:none;'><?php echo '微泰移动视频会议系统',PHP_EOL,'主题：',trim($title),PHP_EOL,'开始时间：',trim($start_time),PHP_EOL,'时长：',trim($time_length),'分钟',PHP_EOL,'参会密码：',trim($password);$url=SROOT.'/meeting/index/'.$id;echo PHP_EOL,"入会链接：{$url}";?></textarea>
     <div id='mail_panel' style='display:none;width:500px;'>
     <form name='send_public_email' onsubmit='return send_public_mail(this)'>
     <table cellspacing="0" cellpadding="20"  height="400" width="100%">
		<tr><td align='right' width='50'>收件人:</td><td><textarea name='receipt' rows='3' style='width:100%'></textarea></td></tr>
		<tr><td align='right'>正文:</td><td><textarea name='contents' rows='15' style='width:100%'><?php echo '主题：',trim($title),PHP_EOL,'开始时间：',trim($start_time),PHP_EOL,'时长：',trim($time_length),'分钟',PHP_EOL,'参会密码：',trim($password),PHP_EOL,'点击入会链接：',SROOT.'/meeting/index/'.$id;?></textarea></td></tr>
		<tr><td colspan='2' align='center'><input type='submit' value='发送' class='btn btnBlueSm mg-r10' /><input type='button' value='取消' onclick='$( "#mail_panel" ).dialog("close")' class='btn btnBlueSm mg-r10'/></td></tr>
	</table>
	</form>
     </div>
     
     <link rel="stylesheet" type="text/css" href="/js/lib/css/ui-lightness/jquery-ui-1.8.19.custom.css" />
    <script src='/js/lib/jquery-ui-1.8.19.custom.min.js'></script>