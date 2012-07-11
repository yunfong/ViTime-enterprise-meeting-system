<?php $this->load->view('/mymeeting/cmp_user_nav.php')?>
<div class="regBox">
    	<form id="updateuserForm" name="updateuserForm" class="regForm" method='post' action='/mymeeting/recharge'>
    	<?php if(!empty($errMsg)):?>
    	<ul>
    		<li>
             	<div class="errorTip">
                 	<span class="icon icon-error"></span><?php echo $errMsg?>
             	</div>
        	</li>
    	</ul>
    	<?php endif;?>
        
    	<?php if($rid):?>
    	<ul>
    		<li>
             	<div class="sysTipOk">
                 	<span class="icon icon-ok"></span><a href="/mymeeting/payment?rid=<?php echo $rid?>" target="_blank">去付款</a>
             	</div>
        	</li>
    	</ul>
    	<?php else:?>
        
        	<ul>
            	<li>
                	<div class="fname"><span class="redStar">*</span><label>充值金额：</label></div>
                    <div class="fvalue"><input type="text" name="money" value='<?php echo $money?>' class="inputStyle" /></div>
                    <div class="ftip"></div>
                </li>
                <li>
                	<div class="fname"><span class="redStar">*</span><label>支付方式：</label></div>
                    <div class="fvalue">
                    <input name="way" type="radio" value="alipay" checked="checked" /> 支付宝
                    </div>
                </li>
                <li>
                	<div class="fname"></div>
                    <div class="fvalue">
                    	<input type="submit" class="btn btnBlue" value='确 定'></input>
                    </div>
                    <div class="ftip"></div>
                </li>
            </ul>
    	<?php endif;?>
        </form>
    </div>