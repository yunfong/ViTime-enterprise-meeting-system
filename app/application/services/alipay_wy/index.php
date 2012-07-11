<?php
/* *
 * 功能：支付宝纯网关接口调试入口页面
 * 版本：3.2
 * 日期：2011-03-17
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<TITLE>支付宝纯网关接口</TITLE>
<script language=JavaScript>
function CheckForm()
{
	if (document.alipayment.subject.value.length == 0) {
		alert("请输入商品名称.");
		document.alipayment.subject.focus();
		return false;
	}
	if (document.alipayment.total_fee.value.length == 0) {
		alert("请输入付款金额.");
		document.alipayment.total_fee.focus();
		return false;
	}
	var reg	= new RegExp(/^\d*\.?\d{0,2}$/);
	if (! reg.test(document.alipayment.total_fee.value))
	{
        alert("请正确输入付款金额");
		document.alipayment.total_fee.focus();
		return false;
	}
	if (Number(document.alipayment.total_fee.value) < 0.01) {
		alert("付款金额金额最小是0.01.");
		document.alipayment.total_fee.focus();
		return false;
	}
	function getStrLength(value){
        return value.replace(/[^\x00-\xFF]/g,'**').length;
    }
    if(getStrLength(document.alipayment.alibody.value) > 200){
        alert("备注过长！请在100个汉字以内");
        document.alipayment.alibody.focus();
        return false;
    }
    if(getStrLength(document.alipayment.subject.value) > 256){
        alert("标题过长！请在128个汉字以内");
        document.alipayment.aliorder.focus();
        return false;
    }

	document.aplipayment.alibody.value = document.aplipayment.alibody.value.replace(/\n/g,'');
}  



</script>
</head>
<style>
*{
	margin:0;
	padding:0;
}
ul,ol{
	list-style:none;
}
.title{
    color: #ADADAD;
    font-size: 14px;
    font-weight: bold;
    padding: 8px 16px 5px 10px;
}
.hidden{
	display:none;
}

.new-btn-login-sp{
	border:1px solid #D74C00;
	padding:1px;
	display:inline-block;
}

.new-btn-login{
    background-color: transparent;
    background-image: url("images/new-btn-fixed.png");
    border: medium none;
}
.new-btn-login{
    background-position: 0 -198px;
    width: 82px;
	color: #FFFFFF;
    font-weight: bold;
    height: 28px;
    line-height: 28px;
    padding: 0 10px 3px;
}
.new-btn-login:hover{
	background-position: 0 -167px;
	width: 82px;
	color: #FFFFFF;
    font-weight: bold;
    height: 28px;
    line-height: 28px;
    padding: 0 10px 3px;
}
.bank-list{
	overflow:hidden;
	margin-top:5px;
}
.bank-list li{
	float:left;
	width:153px;
	margin-bottom:5px;
}

#main{
	width:750px;
	margin:0 auto;
	font-size:14px;
	font-family:'宋体';
}
#logo{
	background-color: transparent;
    background-image: url("images/new-btn-fixed.png");
    border: medium none;
	background-position:0 0;
	width:166px;
	height:35px;
    float:left;
}
.red-star{
	color:#f00;
	width:10px;
	display:inline-block;
}
.null-star{
	color:#fff;
}
.content{
	margin-top:5px;
}

.content dt{
	width:100px;
	display:inline-block;
	text-align:right;
	float:left;
	
}
.content dd{
	margin-left:100px;
	margin-bottom:5px;
}
#foot{
	margin-top:10px;
}
.foot-ul li {
	text-align:center;
}
.note-help {
    color: #999999;
    font-size: 12px;
    line-height: 130%;
    padding-left: 3px;
}
<!-- -->
.icon-info{
    background-color: #D2EEF7;
    border-style: solid solid solid none;
    border-width: 1px 1px 1px medium;
    font-weight: normal;
    height: 30px;
    line-height: 15px;
    padding: 0 3px;
    top: -1px;
    font: 12px/1.5 tahoma,arial,宋体;
}
.icon{
    background-image: url(images/bankicon.png);
    display:inline-block;
    height: 30px;
    width:120px;
    background-repeat: no-repeat;
}
.ICBC{    
    background-position: 0 -40px;
}
.CMB {
    background-position: 0 -80px;
}
.CCB{
    background-position: 0 -320px;
}
.BOC{
    background-position: 0 -520px;
}
.ABC{
    background-position: 0 -480px;
}
.COMM{
    background-position: 0 -600px;
}
.PSBC {
    background-position: 0 -400px;
}
.CEB {
    background-position: 0 -440px;
}
.SPDB {
    background-position: 0 -360px;
}
.GDB {
    background-position: 0 -280px;
}
.CITIC {
    background-position: 0 -200px;
}
.CIB {
    background-position: 0 -3365px;
}
.SDB {
    background-position: 0 -240px;
}
.CMBC {
    background-position: 0 -120px;
}
.HZCB {
    background-position: 0 -760px;
}
.SHBANK {
    background-position: 0 -840px;
}
.BJRCB {
    background-position: 0 -2640px;
}
.SPABANK {
    background-position: 0 -1880px;
}
.FDB {
    background-position: 0 -1320px;
}
.NBBANK {
    background-position: 0 -1240px;
}
.BJBANK {
    background-position: 0 -3240px;
}
.WZCB {
    background-position: 0 -1720px;
}
.ICBCBTB{
    background-image: url(images/ENV_ICBC_OUT.gif);
}
.CCBBTB{
    background-image: url(images/ENV_CCB_OUT.gif);
}
.ABCBTB{
    background-image: url(images/ENV_ABC_OUT.gif);
}
.SPDBBTB{
    background-image: url(images/ENV_SPDB_OUT.gif);
}
<!-- -->
.cashier-nav {
    font-size: 14px;
    margin: 15px 0 10px;
    text-align: left;
    height:30px;
    border-bottom:solid 2px #CFD2D7;
}
.cashier-nav ol li {
    float: left;
}
.cashier-nav li.current {
    color: #AB4400;
    font-weight: bold;
}
.cashier-nav li.last {
    clear:right;
}
.alipay_link {
    text-align:right;
}
.alipay_link a:link{
    text-decoration:none;
    color:#8D8D8D;
}
.alipay_link a:visited{
    text-decoration:none;
    color:#8D8D8D;
}
</style>
<body text=#000000 bgColor=#ffffff leftMargin=0 topMargin=4>
	<div id="main">
		<div id="head">
			<div id="logo"></div>
            <dl class="alipay_link">
                <a target="_blank" href="http://www.alipay.com/"><span>支付宝首页</span></a>|
                <a target="_blank" href="https://b.alipay.com/home.htm"><span>商家服务</span></a>|
                <a target="_blank" href="http://help.alipay.com/support/index_sh.htm"><span>帮助中心</span></a>
            </dl>
            <span class="title">支付宝纯网关付款快速通道</span>
			<!--<div id="title" class="title">支付宝纯网关付款快速通道</div>-->
		</div>
        <div class="cashier-nav">
            <ol>
                <li class="current">1、确认付款信息 →</li>
                <li>2、付款 →</li>
                <li class="last">3、付款完成</li>
            </ol>
        </div>
        <form name=alipayment onSubmit="return CheckForm();" action=alipayto.php method=post target="_blank">
            <div id="body" style="clear:left">
                <dl class="content">
                    <dt>标题：</dt>
                    <dd>
                        <span class="red-star">*</span>
                        <input size=30 name=subject />
                        <span>如：7月5日定货款。</span>
                    </dd>
                    <dt>付款金额：</dt>
                    <dd>
                        <span class="red-star">*</span>
                        <input maxLength=10 size=30 name=total_fee onfocus="if(Number(this.value)==0){this.value='';}" value="00.00"/>
                        <span>如：112.21</span>
                    </dd>
                    <dt>备注：</dt>
                    <dd>
                        <span class="null-star">*</span>
                        <textarea style="margin-left:3px;" name=alibody rows=2 cols=40 wrap="physical"></textarea><br/>
                        <span>（如联系方法，商品要求、数量等。100汉字内）</span>
                    </dd>
                    <dt>支付方式：</dt>
                    <dd>
                        <span class="null-star">*</span>
                        <div>
                            <input type="radio" name="pay_bank" value="directPay" checked><img src="images/alipay.gif" border="0" style="height:35px;width:120px" />
                        </div>
                        <ul class="bank-list">
                            <li><input type="radio" name="pay_bank" value="ICBCB2C"/><span class="icon ICBC"></span></li>
                            <li><input type="radio" name="pay_bank" value="CMB"/><span class="icon CMB"></span></li>
                            <li><input type="radio" name="pay_bank" value="CCB"/><span class="icon CCB"></span></li>
                            <li><input type="radio" name="pay_bank" value="BOCB2C"><span class="icon BOC"></span></li>
                            <li><input type="radio" name="pay_bank" value="ABC"/><span class="icon ABC"></span></li>
                            <li><input type="radio" name="pay_bank" value="COMM"/><span class="icon COMM"></span></li>
                            <li><input type="radio" name="pay_bank" value="PSBC-DEBIT"/><span class="icon PSBC"></span></li>
                            <li><input type="radio" name="pay_bank" value="CEBBANK"/><span class="icon CEB"></span></li>
                            <li><input type="radio" name="pay_bank" value="SPDB"/><span class="icon SPDB"></span></li>
                            <li><input type="radio" name="pay_bank" value="GDB"><span class="icon GDB"></span></li>
                            <li><input type="radio" name="pay_bank" value="CITIC"/><span class="icon CITIC"></span></li>
                            <li><input type="radio" name="pay_bank" value="CIB"/><span class="icon CIB"></span></li>
                            <li><input type="radio" name="pay_bank" value="SDB"><span class="icon SDB"></span></li>
                            <li><input type="radio" name="pay_bank" value="CMBC"/><span class="icon CMBC"></span></li>
                            <li><input type="radio" name="pay_bank" value="BJBANK"/><span class="icon BJBANK"></span></li>
                            <li><input type="radio" name="pay_bank" value="HZCBB2C"/><span class="icon HZCB"></span></li>
                            <li><input type="radio" name="pay_bank" value="SHBANK"/><span class="icon SHBANK"></span></li>
                            <li><input type="radio" name="pay_bank" value="BJRCB"/><span class="icon BJRCB"></span></li>
                            <li><input type="radio" name="pay_bank" value="SPABANK"/><span class="icon SPABANK"></span></li>
                            <li><input type="radio" name="pay_bank" value="FDB"/><span class="icon FDB"></span></li>
                            <li><input type="radio" name="pay_bank" value="WZCBB2C-DEBIT"><span class="icon WZCB"></span></li>
                            <li><input type="radio" name="pay_bank" value="NBBANK "><span class="icon NBBANK"></span></li> 
                            <li><input type="radio" name="pay_bank" value="ICBCBTB"/><span class="icon ICBCBTB"></span></li>
                            <li><input type="radio" name="pay_bank" value="CCBBTB"/><span class="icon CCBBTB"></span></li>
                            <li><input type="radio" name="pay_bank" value="SPDBB2B"/><span class="icon SPDBBTB"></span></li>
                            <li><input type="radio" name="pay_bank" value="ABCBTB"/><span class="icon ABCBTB"></span></li>
                        </ul>
                    </dd>
                    <dt></dt>
                    <dd>
                        <span class="new-btn-login-sp">
                            <button class="new-btn-login" type="submit" style="text-align:center;">确认付款</button>
                        </span>
                    </dd>
                </dl>
            </div>
		</form>
        <div id="foot">
			<ul class="foot-ul">
				<li>
					<font class=note-help>如果您点击“确认付款”按钮，即表示您同意向卖家购买此物品。 
					  <br/>
					  您有责任查阅完整的物品登录资料，包括卖家的说明和接受的付款方式。卖家必须承担物品信息正确登录的责任！ 
					</font>
				</li>
				<li>
					支付宝版权所有 2011-2015 ALIPAY.COM 
				</li>
			</ul>
			<ul>
		</div>
	</div>
</body>
</html>
