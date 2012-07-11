<?php $this->load->view('/mymeeting/cmp_admin_nav.php')?>
<div class="reserBox">
    	<div class="col">
            <h3>进入一场会议</h3>
            <div class="colBox">
                <form id="reserForm" name="reserForm" method='post' action='/company/do_enter_meeting'>
                <input type='hidden' name='meet_id' value='<?php echo $id ?>' />
                    <ul>
                    <li>
                            主题：<?php echo $title?>
                        </li>
                        <li>
                            开始时间：<?php echo $start_time?> 
                        </li>
                        <li>
                            时长：<?php echo $time_length?> Min
                        </li>
                        <li>
                            <div>请输入您要参加的会议的会议密码：</div>
                            <input type="password" name="password" class="inputStyle">
                            <input type="submit" class="btn btnBlue" value='进入会议'></input>
                            <?php if(!empty($errMsg)):?>
                            <div class="errorTip">
                     			<span class="icon icon-error"></span><?php echo $errMsg?>                   
                     		 </div>
                            <?php endif;?>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </div>