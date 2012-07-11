<?php $this->load->view('/admin/nav.php')?>
<div class='pageBox'>
<?php echo makepage($page,$total,'',10);?>
</div>
    <div class="reserBox">
    	<div class="col">
            <h3>企业列表</h3>
            <div class="colBox" style="padding:10px 0px 0px;">
                <table width="100%" border="0" class="dataGrid">
                  <thead>
                  	<tr>
                    	<th class="td1">企业名称</th>
                    	<th class="td1">企业标识</th>
                        <th class="td2">账号</th>
                        <th class="td3">密码</th>
                        <th class="td4">电话号码</th>
                        <th class="td4">邮箱</th>
                        <th class="td4">试用截至日期</th>
                        <th class="td5">操作</th>
                    </tr>
                  </thead>
                  <?php if(!empty($data)):?>
	                  <?php foreach($data as $user):?>
	                  <tr>
	                    <td class="td1"><?php echo stripslashes($user['company_name'])?></td>
	                    <td class="td1"><?php echo stripslashes($user['company_mark'])?></td>
	                    <td class="td1"><?php echo stripslashes($user['username'])?></td>
	                    <td class="td3">******</td>
	                    <td class="td4"><?php echo $user['mobile']?></td>
	                    <td class="td5"><?php echo $user['email']?></td>
	                    <?php if($user['onTry']== 0):?>
	                    <td class="td5">未试用</td>
	                    <?php elseif ($user['onTry']== 1):?>
	                    <td class="td5" style='color:green' title='正在试用期，<?php echo $user['tryDate']?>收回试用'><?php echo $user['tryDate']?></td>
	                    <?php else:?>
	                    <td class="td5" style='color:gray' title='已于 <?php echo $user['tryDate']?> 收回试用'><?php echo $user['tryDate']?></td>
	                    <?php endif;?>
	                    <td class="td5">
						<button type="submit" class="btn btnBlueSm mg-r10" onclick='toUpdateCmp(<?php echo $user['id'],',',$user['company_id'];?>)'>修改</button>
						<button type="submit" class="btn btnBlueSm" onclick='delete_company(<?php echo $user['id'],',',$user['company_id'];?>)'>删除</button>
						</td>
	                  </tr>
	                  <?php endforeach;?>
	               <?php else:?>
	               <tr><td colspan="6" align='center'>还没有企业注册</td></tr>
	               <?php endif;?>
                </table>
            </div>
        </div>
    </div>
    <div class="pageBox">
    	<?php echo makepage($page,$total,'',10);?>
    </div>
    <script src='/js/admin.js'></script>
    <form name='deletecompanyForm' action='/admin/delete_company' method='post'>
    <input type='hidden' name='cmp_id' />
    <input type='hidden' name='user_id' />
    </form>