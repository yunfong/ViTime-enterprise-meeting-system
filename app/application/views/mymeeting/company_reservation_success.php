<?php $this->load->view('/mymeeting/cmp_user_nav.php')?>
<div class="sysTipOk">
    	<span class="icon icon-ok"></span>
        预约成功！
    </div>
    <div class="regSuccess">
    	<div class="regSuccessTip">
        	<span class="icon icon-att"></span>
            <div class="regTxt">
            	会议主题：<?php echo $title?><br>会议时间：<?php echo $start_time?>-<?php echo (substr($start_time,0,10)==substr($end_time,0,10)?substr($end_time,10):$end_time); ?>
            	
            </div>
        </div>
    </div>
    <div class="regBot">
    	<div class="col">
            <h3>您可以通过以下方式通知会议的参会者：</h3>
            <div class="colBox">
                <table border="0">
                  <tr>
                    <td class="td1"><button type="button" class="btn btnBlue" onclick="send_company_mail(<?php echo $id;?>)">发送邮件</button></td>
                    <td class="td2">使用电脑默认邮件客户端发送会议邀请给会议的参与者。</td>
                  </tr>
                  <tr>
                    <td class="td1"><button type="button" class="btn btnBlue" id='copy_button' >复制会议信息</button></td>
                    <td class="td2">复制会议的详细信息，使用聊天工具或者网页邮箱发送给与会者。</td>
                        
                  </tr>
                  <tr>
                    <td class="td1"><button type="button" class="btn btnBlue" onclick='send_sms(<?php echo $id;?>)'>发送短信</button></td>
                    <td class="td2">点击发送短信，填写完相关号码则系统自动把会议信息发送到与会者的手机上。</td>
                  </tr>
                </table>
            </div>
        </div>
    </div>
<textarea id='meeting-content' style='display:none;'><?php echo '微泰移动视频会议系统',PHP_EOL,'主题：',trim($title),PHP_EOL,'开始时间：',trim($start_time),PHP_EOL,'时长：',trim($time_length),'分钟',PHP_EOL,'参会人员：'?><?php $username = ''; foreach($user_list as $user):?><?php  $username .= trim($user['username']).','?><?php endforeach;echo trim(rtrim($username,',')); $url=SROOT.'/meeting/index/'.$id;echo PHP_EOL,"入会链接：{$url}";?></textarea>