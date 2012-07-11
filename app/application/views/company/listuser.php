<?php $this->load->view('/company/cmp_admin_nav.php')?>
<div class='pageBox'>
<?php echo makepage($page,$total,'',10);?>
</div>
    <div class="reserBox">
    	<div class="col">
            <h3>用户列表</h3>
            <div class="colBox" style="padding:10px 0px 0px;">
                <table width="100%" border="0" class="dataGrid ">
                  <thead>
                  	<tr>
                    	<th class="td1">姓名</th>
                        <th class="td2">账号</th>
                        <th class="td3">密码</th>
                        <th class="td4">电话号码</th>
                        <th class="td5">邮箱</th>
                        <th class="td5">操作</th>
                    </tr>
                  </thead>
                  <?php if(!empty($data)):?>
	                  <?php foreach($data as $user):?>
	                  <tr>
	                    <td class="td1"><?php echo stripslashes($user['name'])?></td>
	                    <td class="td1"><?php echo stripslashes($user['username'])?></td>
	                    <td class="td3">******</td>
	                    <td class="td4"><?php echo $user['mobile']?></td>
	                    <td class="td5"><?php echo $user['email']?></td>
	                    <td class='td5'>
	                    <button type="submit" class="btn btnBlueSm mg-r10" onclick='update_company_user(<?php echo $user['id'];?>)'>修改</button>
						<button type="submit" class="btn btnBlueSm" onclick='delete_company_user(<?php echo $user['id'];?>)'>删除</button>
						</td>
	                  </tr>
	                  <?php endforeach;?>
	               <?php else:?>
	               <tr><td colspan="6" align='center'>还没有用户</td></tr>
	               <?php endif;?>
                </table>
            </div>
        </div>
    </div>
    <div class="pageBox">
    	<?php echo makepage($page,$total,'',10);?>
    </div>
    