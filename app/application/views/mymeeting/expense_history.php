<?php $this->load->view('/mymeeting/cmp_user_nav.php')?>
<?php $this->load->view('/mymeeting/profile_menu.php')?>
<div class="regBox" style='padding-top:0px;width:800px;'>
    	<div class="col">
            <h3>消费记录</h3>
            <div class="colBox" style="padding:10px 0px 0px;">
                <table width="100%" border="0" class="dataGrid borderGrid">
                  <thead>
                  	<tr>
                    	<th class="td4" width="20%">消费时间</th>
                        <th class="td2">消费金额</th>
                        <th class="td2">备注</th>
                        <th class="td2">类别</th>
                    </tr>
                  </thead>
                  <?php if(!empty($data)):?>
	                  <?php foreach($data as $pay):?>
	                  <tr>
	                    <td class="td1"><?php echo date('Y-m-d',strtotime($pay['pay_time']))?></td>
	                    <td class="td1" style='font-family: Verdana,Arial'>￥<?php echo number_format($pay['r_money'],2)?></td>
	                    <td class="td1" style='font-family: Verdana,Arial'><?php echo $pay['note']?></td>
	                    <td class="td1"><?php echo $pay['pay_type']==1?'会议支出':'月租'?></td>
	                  </tr>
	                  <?php $meeting=null; endforeach;?>
	               <?php else:?>
	               <tr><td colspan="6" align='center'>还没有消费记录</td></tr>
	               <?php endif;?>
                </table>
            </div>
        </div>
    <div class="pageBox">
    	<?php echo makepage($page,$total,'',10);?>
    </div>
</div>
    