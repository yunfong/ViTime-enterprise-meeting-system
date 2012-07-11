<?php $this->load->view('/mymeeting/cmp_user_nav.php')?>
<div class="reserBox">
    	<div class="col">
            <h3>安排会议</h3>
            <div class="colBox">
                <form id="reserForm" name="reserForm" method='post' action='/mymeeting/do_company_reservation' onsubmit="return do_open_meeting(this)">
                <input type='hidden' name='user_list'>
                    <ul>
                        <li>
                            <div><span class="redStar">*</span>会议主题 <span class="stip">最多输入50个字</span></div>
                            <input type="text" name="title" class="inputStyle"  maxlength='50' value='<?php echo $title?>'/>
                        </li>
                        <li>
                            <div>开始时间</div>
                            <input name='start_time' id='start_time' class='year' value='<?php echo $start_time?>' />
                            <select name="hour" class="month" >
                               <?php for($m = 00;$m<=23;$m++):?>
                            	<option value='<?php echo $m;?>' <?php echo $hour == $m?'selected':'';?>><?php echo str_pad($m,2, '0',STR_PAD_LEFT);?></option>
                            	<?php endfor;?>
                            </select>
                            
                            	
                            <select name="minutes" class="day">
                            	<?php $maxM = 59; for($M = 0;$M<=$maxM;$M +=5):?>
                            	<option value='<?php echo $M;?>' <?php echo $minutes == $M?'selected':'';?>><?php echo str_pad($M,2, '0',STR_PAD_LEFT);?></option>
                            <?php endfor;?>
                            </select>
                        </li>
                        <li>
                            <div>会议时长(分钟)</div>
                             <input name='time_length' class='year max100' value='<?php echo $time_length?>' />
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </div>
    	<?php if(!empty($errMsg)):?>
    	<ul>
    		<li>
             	<div class="errorTip">
                 	<span class="icon icon-error"></span><?php echo $errMsg?>
             	</div>
        	</li>
    	</ul>
    	<?php endif;?>
    <div class="reserBox">
        <div class="col left">
            <h3><span style='float:left;'>用户列表</span><span class='selectAllButton'><button type='button' onclick='selectAll()' />全选</button></span></h3>
            <div class="colBox">
                <div class="userlist" id='left_user_list'>
                <?php foreach($user_list as $user):?>
                	<span title="<?php echo (!empty($user['name'])?$user['name']:$user['username'])?>"><input type="checkbox" value='<?php echo $user['id']?>'/><label ><?php echo mb_substr((!empty($user['name'])?$user['name']:$user['username']),0,12,'utf8');?></label></span>
                <?php endforeach;?>    
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col right">
            <h3><span style='float:left;'>已选用户<span id='selectedUser'></span></span><span class='selectAllButton'><button type='button' onclick='unSelectAll()' />清空</button></span></h3>
            <div class="colBox" >
                <div class="userlist" id='right_user_list'>
                
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="reserBtn">
        <button type="submit" class="btn btnBlue" onclick='do_open_meeting(document.forms.reserForm)'>保存</button>
        <button type="button" class="btn btnRed" onclick='window.location.href="/mymeeting/company_meeting"'>取消</button>
    </div>
    <link rel="stylesheet" type="text/css" href="/js/lib/css/ui-lightness/jquery-ui-1.8.19.custom.css" />
    <script src='/js/lib/jquery-ui-1.8.19.custom.min.js'></script>
    <script src='/js/lib/jquery.ui.datepicker-zh-CN.js'></script>
