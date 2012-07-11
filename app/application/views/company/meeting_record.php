<?php $this->load->view('/company/cmp_admin_nav.php')?>
<?php $this->load->view('/company/profile_menu.php')?>
<div class="regBox" style='padding-top:0px;width:800px;'>
    	<div class="col">
            <h3>会议记录</h3>
            <div class="colBox" style="padding:10px 0px 0px;">
                <table width="100%" border="0" class="dataGrid borderGrid">
                  <thead>
                  	<tr>
                    	<th class="td4" width="20%">会议时间</th>
                        <th class="td2">主题</th>
                        <th class="td3"  width="20%">时长</th>
                        <th class="td4"  width="20%">费用</th>
                    </tr>
                  </thead>
                  <?php if(!empty($data)):?>
	                  <?php foreach($data as $meeting):?>
	                  <tr>
	                    <td class="td1"><?php echo date('Y-m-d H:i',strtotime($meeting['start_time']))?></td>
	                    <td class="td1"><?php echo $meeting['title']?></td>
	                    <td class="td3"><?php echo $meeting['time_length']?> Min</td>
	                    <td class="td4"><?php echo $meeting['type']=='1'?'企业会议':$meeting['r_money']?></td>
	                  </tr>
	                  <?php $meeting=null; endforeach;?>
	               <?php else:?>
	               <tr><td colspan="6" align='center'>还没有会议记录</td></tr>
	               <?php endif;?>
                </table>
            </div>
        </div>
    <div class="pageBox">
    	<?php echo makepage($page,$total,'',10);?>
    </div>
</div>
    