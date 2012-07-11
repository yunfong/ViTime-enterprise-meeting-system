
    <div class="sysTipOk">
    	<span class="icon"></span>
        信息提示！
    </div>
    <div class="regSuccess">
    	<div class="regSuccessTip">
            <div class="regTxt">
            <p><?php echo $message?></p>
            <p class="op" style="font-size:14px; text-align:right; padding-right:50px">
            <?php if($url_forward):?>
                <a href="<?php echo $url_forward?>">页面跳转中...</a>
            <?php else:?>
                <a href="javascript:history.go(-1);">返回上一页</a> | 
                <a href="/">返回首页</a>
            <?php endif;?>
            </p>
            </div>
        </div>
    </div>
<script language="javascript"  type="text/javascript" src="do.php?ac=sendmail&rand=$_SGLOBAL[timestamp]"></script>
