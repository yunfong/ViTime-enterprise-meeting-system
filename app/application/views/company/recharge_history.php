<?php $this->load->view('/company/cmp_admin_nav.php')?>
<?php $this->load->view('/company/profile_menu.php')?>
<div class="regBox" style='padding-top:0px;width:800px;'>
    	<div class="col">
            <h3>充值记录</h3>
            <div class="colBox" style="padding:10px 0px 0px;">
                <table width="100%" border="0" class="dataGrid borderGrid">
                  <thead>
                  	<tr>
                    	<th class="td4" width="20%">充值时间</th>
                        <th class="td2">充值金额</th>
                        <th class="td2">订单号</th>
                        <th class="td2">支付宝交易号</th>
                        <th class="td2">处理状态</th>
                    </tr>
                  </thead>
                  <?php if(!empty($data)):?>
	                  <?php foreach($data as $recharge):?>
	                  <tr>
	                    <td class="td1"><?php echo date('Y-m-d H:i',$recharge['uptime'])?></td>
	                    <td class="td1" style='font-family: Verdana,Arial'>￥<?php echo number_format($recharge['money'],2)?></td>
	                    <td class="td1" style='font-family: Verdana,Arial'><?php echo $recharge['oid']?></td>
	                    <td class="td1" style='font-family: Verdana,Arial'><?php echo $recharge['trade_no']?></td>
	                    <td class="td1"><?php echo $recharge['isdeal']==1?'已处理':'未处理'?></td>
	                  </tr>
	                  <?php $meeting=null; endforeach;?>
	               <?php else:?>
	               <tr><td colspan="6" align='center'>还没有充值记录</td></tr>
	               <?php endif;?>
                </table>
            </div>
        </div>
    <div class="pageBox">
    	<?php echo makepage($page,$total,'',10);?>
    </div>
</div>
    