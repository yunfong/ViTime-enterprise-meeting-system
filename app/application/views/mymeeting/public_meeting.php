<?php $this->load->view('/mymeeting/cmp_user_nav.php')?>
<div class='pageBox'>
<?php echo makepage($page,$total,'',10);?>
</div>
<div class="reserBox">
    	<div class="col">
            <h3>会议室列表</h3>
            <div class="colBox" style="padding:10px 0px 0px;">
                <table width="100%" border="0" class="dataGrid">
                  <thead>
                  	<tr>
                    	<th class="td1">会议号</th>
                        <th class="td2">会议主题</th>
                        <th class="td3">开始时间</th>
                        <th class="td4">会议时长</th>
                        <th class="td5">状态</th>
                    </tr>
                  </thead>
                  <?php foreach($data as $meeting):?>
                  <tr class='meeting_row' id='meeting-<?php echo $meeting['id'] ?>'>
                    <td class="td1"><?php echo $meeting['id']?></td>
                    <td class="td2"><?php echo $meeting['title']?></td>
                    <td class="td3"><?php echo $meeting['start_time']?>
                    <?php if(strtotime($meeting['end_time']) < time()):?>
                    &nbsp;<span style='color:red;'>已过期</span>
                    <?php endif;?>
                    </td>
                    <td class="td4"><?php echo $meeting['time_length']?>Min</td>
                    <td class="td5">
                    
                    <?php if(strtotime($meeting['end_time']) > time()):?>
                    	<button type="button" class="btn btnBlueSm mg-r10" onclick='enter_meeting(<?php echo $meeting['id']?>)'>进入</button>
                    <?php endif;?>
                    <?php if($meeting['state']==1 && ($meeting['company_id']==$login_user->company_id &&  $meeting['user_id'] == $login_user->id)):?>
                    	<button type="button" class="btn btnBlueSm mg-r10" onclick = 'edit_public_meeting(<?php echo $meeting['id']?>)'>编辑</button>
                        <button type="button" class="btn btnBlueSm" onclick='delete_public_meeting(<?php echo $meeting['id']?>)'>删除</button>
                    <?php endif;?>
                    
                    </td>
                  </tr>
                  <?php endforeach;?>
                  <tr class="lastTr">
                    <td colspan="4"></td>
                    <td class="td5">
                    	<button type="button" class="btn btnBlueSm" onclick='open_public_meeting()'>预约</button>
                    </td>
                  </tr>
                </table>
            </div>
        </div>
    </div>
    <div class='pageBox'>
<?php echo makepage($page,$total,'',10);?>
</div>
