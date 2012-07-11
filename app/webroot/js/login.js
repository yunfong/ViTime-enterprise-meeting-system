
function admin_login(){
	var form = document.getElementById('loginForm');
	
	form.submit();
}

function login(){
var form = document.getElementById('loginForm');
	
	form.submit();
}

function validRegForm(form){

	if($.trim(form.username.value) == ""){
		alert('请输入用户名');
		$('#username_ftip').show().find('.attTip').addClass('errorTip');
		return false;
	}else{
		$('#username_ftip .attTip').removeClass('errorTip');
	}
	
	if($.trim(form.password.value) == ""){
		alert('请输入密码');
		$('#password_ftip').show().find('.attTip').addClass('errorTip');
		return false;
	}else{
		$('#password_ftip .attTip').removeClass('errorTip');
	}
	
	if(form.password.value != form.repassword.value){
		alert('两次密码输入不一致，请重新输入');
		$('#repassword_ftip').show().find('.attTip').addClass('errorTip');
		return false;
	}else{
		$('#repassword_ftip .attTip').removeClass('errorTip');
	}
	
	if(form.mobile.value==''){
		alert('请输入手机号码');
		$('#mobile_ftip').show().find('.attTip').addClass('errorTip');
		return false;
	}else{
		$('#mobile_ftip .attTip').removeClass('errorTip');
	}
	
	if(form.email.value==''){
		alert('请输入邮箱地址');
		$('#email_ftip').show().find('.attTip').addClass('errorTip');
		return false;
	}else{
		$('#email_ftip .attTip').removeClass('errorTip');
	}
	
	if($.trim(form.company_name.value)==''){
		alert('请输入企业名称');
		$('#company_name_ftip').show().find('.attTip').addClass('errorTip');
		return false;
	}else{
		$('#company_name_ftip .attTip').removeClass('errorTip');
	}
	
	if($.trim(form.company_mark.value)==''){
		alert('请输入企业标识');
		$('#company_mark_ftip').show().find('.attTip').addClass('errorTip');
		return false;
	}else{
		$('#company_mark_ftip .attTip').removeClass('errorTip');
	}
	
	if($.trim(form.verifycode.value) == ""){
		alert('请输入验证码');
		$('#verifycode_ftip').show();
		return false;
	}else{
		$('#verifycode_ftip').hide();
	}
	
	if(!form.view_agreement.checked){
		alert('请先阅读《企业移动视频会议协议》');
		return false;
	}
	return true;
}

function validForm(form){
	
}

function changeVerify(){
//	document.getElementById('verifyCode').src = "/captcha.php?time="+(new Date().getTime());
//	alert(document.getElementById('verifyCode').src );
	$('#verifyCode').attr('src','/captcha.php?'+new Date().getTime());
}