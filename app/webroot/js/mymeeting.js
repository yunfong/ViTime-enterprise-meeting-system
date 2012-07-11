/**
 * 企业管理员
 */

//会议查看用户
var meeting_user_list = {};
jQuery(function(){
	$('.meeting_row').click(function(){
		$('.selected-tr').removeClass('selected-tr');
		$(this).addClass('selected-tr');
		var id = this.id.split('-').pop();
		if(typeof(meeting_user_list[id]) == 'object' && meeting_user_list[id].length >0){
			return render_meeting_user_list(meeting_user_list[id]);
		}
		$.getJSON('/mymeeting/get_meeting_user_list/'+id,function(list){
			if(typeof(list)=='object' && list.length>0){
				meeting_user_list[id] = list;
			}
			render_meeting_user_list(list);
		});
	});
});
//渲染用户
function render_meeting_user_list(list){
	$('#meeting_user_list').empty();
	$.each(list ,function(index,item){
		$('#meeting_user_list').append('<div class="spanUser"><span class="icon icon-freg"></span><span class="username">'+((item.name!='')?item.name:item.username)+'</span></div>');
	});
	$('#meeting_user_list').append('<div class="clearfix"></div>');
	$('#meeting_user_list').parent().parent().show();

}



//进入会议
function enter_meeting(meeting_id){
	window.location.href = '/meeting/index/'+meeting_id;
}

//编辑会议
function edit_company_meeting(meeting_id){
	window.location.href = '/mymeeting/edit_company_reservation/'+meeting_id;
}
//编辑会议
function edit_public_meeting(meeting_id){
	window.location.href = '/mymeeting/edit_public_reservation/'+meeting_id;
}
//删除会议
function delete_company_meeting(meeting_id){
	if(meeting_id == ''){
		alert('参数错误');
		return;
	}
	if(!confirm('您确定要取消该会议吗？')){
		return false;
	}
	$.post('/mymeeting/delete_meeting',{meet_id:meeting_id},function(res){
		try{
			eval('res='+res);
		}catch(ex){
			alert('发送错误了！');
		}
		if(res.status == 1){
			alert(res.msg);
			window.location.reload();
		}else{
			alert(res.msg);
		}
	});
}

//预约会议
function open_meeting(){
	window.location.href = '/mymeeting/company_reservation';
}

function do_open_meeting(form){
	if($.trim(form.title.value) == ""){
		alert('会议主题不能为空');
		return false;
	}
	if($.trim(form.start_time.value) ==''){
		alert('会议开始时间必须填写');
		return false;
	}
	form.time_length.value = parseInt(form.time_length.value,10);
	if(isNaN(form.time_length.value) || form.time_length.value >999 || form.time_length.value <0){
		alert("请正确填写会议时长，会议时长必须是在0-999分钟范围内");
		return false;
	}
	
	var user_list = [];
	$('#right_user_list :checkbox').each(function(){
		user_list.push(this.value);
	});
	
	if(user_list.length < 2 || user_list.length > 200){
		alert("会议参与人数错误，会议人数最少2人，最多200人");
		return false;
	}

	form.user_list.value = user_list.join(',');
	form.submit();
}

//预约公共会议
function open_public_meeting(){
	window.location.href = '/mymeeting/public_reservation';
}


function do_open_public_meeting(form){
	if($.trim(form.title.value) == ""){
		alert('会议主题不能为空');
		return false;
	}
	if($.trim(form.start_time.value) ==''){
		alert('会议开始时间必须填写');
		return false;
	}
	if($.trim(form.start_time.value) ==''){
		alert('会议开始时间必须填写');
		return false;
	}
	form.time_length.value = parseInt(form.time_length.value,10);
	if(isNaN(form.time_length.value) || form.time_length.value >999 || form.time_length.value <0){
		alert("请正确填写会议时长，会议时长必须是在0-999分钟范围内");
		return false;
	}
	form.usercount.value = parseInt(form.usercount.value,10);
	if(isNaN(form.usercount.value) || form.usercount.value >200 || form.usercount.value <2){
		alert("会议参与人数错误，会议人数最少2人，最多200人");
		return false;
	}
	
	if(public_meeting_pay_tip(form))
		form.submit();
}

function do_edit_public_meeting(form){
	if($.trim(form.title.value) == ""){
		alert('会议主题不能为空');
		return false;
	}
	if($.trim(form.start_time.value) ==''){
		alert('会议开始时间必须填写');
		return false;
	}
	if($.trim(form.start_time.value) ==''){
		alert('会议开始时间必须填写');
		return false;
	}
	
	form.time_length.value = parseInt(form.time_length.value,10);
	if(isNaN(form.time_length.value) || form.time_length.value >999 || form.time_length.value <0){
		alert("请正确填写会议时长，会议时长必须是在0-999分钟范围内");
		return false;
	}
	form.usercount.value = parseInt(form.usercount.value,10);
	if(isNaN(form.usercount.value) || form.usercount.value >200 || form.usercount.value <2){
		alert("会议参与人数错误，会议人数最少2人，最多200人");
		return false;
	}
	var oldusercount = form.oldusercount.value;
	var oldstarttime = form.oldstarttime.value;
	var isouttime = form.isouttime.value;
	var usercount = form.usercount.value;
	if(isouttime == 1 ){
		if(confirm("该会议已经过期，重新开启会议需要支付费用：\r\n"+
					"您选择了"+usercount+"个人参与会议，需要付费：￥"+(parseFloat(usercount)*USER_PUB_PAY)+"，确定要付费吗？")){
			form.submit();
			return;
		}else{
			return false;
		}
	}
	if(usercount > oldusercount){
		if(confirm("您增加了"+(usercount - oldusercount)+"个用户参与会议，需要付费：￥"+(parseFloat(usercount - oldusercount)*USER_PUB_PAY)+"，确定要付费吗？")){
			form.submit();
			return;
		}else{
			return false;
		}
	}
	form.submit();
}

function do_edit_meeting(form){
	if($.trim(form.title.value) == ""){
		alert('会议主题不能为空');
		return false;
	}
	if($.trim(form.start_time.value) ==''){
		alert('会议开始时间必须填写');
		return false;
	}
	form.time_length.value = parseInt(form.time_length.value,10);
	if(isNaN(form.time_length.value) || form.time_length.value >999 || form.time_length.value <0){
		alert("请正确填写会议时长，会议时长必须是在0-999分钟范围内");
		return false;
	}

	var user_list = [];
	$('#right_user_list :checkbox').each(function(){
		user_list.push(this.value);
	});
	
	if(user_list.length < 2 || user_list.length > 200){
		alert("会议参与人数错误，会议人数最少2人，最多200人");
		return false;
	}

	form.user_list.value = user_list.join(',');
	form.submit();
}

function validateCPWDForm(form){
	if(form.password.value == ''){
		alert('原密码不能为空');
		return false;
	}
	if(form.newpassword.value== ''){
		alert('新密码不能为空');
		return false;
	}

	if(form.newpassword.value != form.renewpassword.value){
		alert('两次密码输入不一致，请重新输入');
		return false;
	}
	return true;
}