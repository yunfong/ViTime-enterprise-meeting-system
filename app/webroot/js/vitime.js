/**
 * 全局函数
 */

jQuery(function(){
	fn_copy();
	max100();
	max200();
});

function max100(){
	$('.max100').bind('keyup',function(evt){
	    return _max100(this,evt);		    
	 });
	
	function _max100(input,evt){
		try{
			var value = input.value;
			var length = value.length;
			for(var i = 0;i<length;i++){
				if(isNaN(value.charAt(i))){
					value = value.replace(value.charAt(i),'A');
				}
			}
			input.value = value.replace(/[A|\s]+/g,'');
		}catch(e){
		}
		input.value = input.value.substr(0,3);
	}
	return true;
}

function max200(){
	$('.max200').bind('keyup',function(evt){
	    return _max200(this,evt);		    
	 });
	
	function _max200(input,evt){
		try{
			var value = input.value;
			var length = value.length;
			for(var i = 0;i<length;i++){
				if(isNaN(value.charAt(i))){
					value = value.replace(value.charAt(i),'A');
				}
			}
			input.value = value.replace(/[A|\s]+/g,'');
		}catch(e){
		}
		input.value = input.value.substr(0,3);
		if(parseInt(input.value,10)>200 ){
			input.value = 200;
		}else{
			if(parseInt(input.value,10)<2 )input.value = 2;
		}
		return true;
	}
}


/**
 * 配置表单验证
 */
function loadFormValid(){
	$(".validform").Validform({//所有可传入的参数如下;
		btnSubmit:"#btn_sub",
		tiptype:1,
		tipSweep:true,
		showAllError:false,
		postonce:true,
		ajaxPost:true,
		datatype:{//传入自定义datatype类型，可以是正则，也可以是函数（函数内可以获得两个参数）;
			"*6-16": /^[^\s]{6,20}$/,
			"z2-4" : /^[\u4E00-\u9FA5\uf900-\ufa2d]{2,4}$/,
			"username":function(gets,obj){
				//参数gets是获取到的表单元素值，obj为当前表单对象;
				var reg1=/^[\w\.]{4,16}$/,
				reg2=/^[\u4E00-\u9FA5\uf900-\ufa2d]{2,8}$/;

				if(reg1.test(gets)){return true;}
				if(reg2.test(gets)){return true;}
				return false;
			}
		}

	});
}

function fn_copy() {
	var copy_button = document.getElementById('copy_button');
	if(!copy_button){
		return;
	}
	var meetingContent = $('#meeting-content').val();
	if(window.clipboardData){
		$(copy_button).click(function(){
			window.clipboardData.setData("Text", meetingContent);
	        debug_log('复制内容：'+meetingContent);
	        return alert("已复制到剪贴板");
		});
	} else{
		ZeroClipboard.setMoviePath('/js/ZeroClipboard.swf');
    	var clip = new ZeroClipboard.Client();
    	clip.setHandCursor( true );
    	clip.addEventListener('load', function (client) {
    		debug_log("Flash movie loaded and ready.");
    		if(meetingContent){
    			clip.setText( meetingContent );
    		}
		});

		clip.addEventListener('mouseOver', function (client) {
			clip.setText( meetingContent );
		});

    	clip.addEventListener('complete', function (client, text) {
    		debug_log('复制内容：'+text);
    		return alert("已复制到剪贴板");
		});
    	clip.glue('copy_button');
    }
    //alert("您的浏览器不支持复制功能，无法完成复制");
    return false;
}



function debug_log(msg) {
	if(typeof(console)!='undefined' && typeof(console.log)!='undefined'){
		console.log(msg);
	}
}

function send_sms(meet_id){
	jNotify("正在发送...",{autoHide:false,ShowOverlay:true});
	$.post('/'+_controller+'/sendsms',{meet_id:meet_id},function(res){
		try{
			eval("res="+res);
		}catch(e){
			return alert('发生错误');
		}
		jNotifyClose();
		alert(res.msg);
	})
}

function send_company_mail(meet_id){
	jNotify("正在发送...",{autoHide:false,ShowOverlay:true});
	$.post('/'+_controller+'/sendmail',{meet_id:meet_id},function(res){
		jNotifyClose();
		try{
			eval("res="+res);
		}catch(e){
			return alert('发生错误');
		}

		alert(decodeURIComponent(res.msg).replace('+', " "));
		if(res.url){
			window.open (res.url,500,500);
		}
	})
}

function to_send_public_mail(){
	$( "#mail_panel" ).dialog({width:"500px",height:'auto',title:'发送会议通知'});
}
function send_public_mail(form){
	var receipt = form.receipt.value;
	var content = form.contents.value;
	jNotify("正在发送...",{autoHide:false,ShowOverlay:true});
	$.post('/'+_controller+'/sendpubmail',{receipt:receipt,content:content},function(res){
		jNotifyClose();
		try{
			eval("res="+res);
		}catch(e){
			return alert('发生错误');
		}

		alert(decodeURIComponent(res.msg).replace('+', " "));
		if(res.url){
			window.open (res.url,500,500);
		}
		if(!res.error){
			$( "#mail_panel" ).dialog("close");
		}
	});
	return false;
}

function public_meeting_pay_tip(form){
	var usercount = form.usercount.value;
	if(usercount > 0 && !isOnTry){
		if(!confirm("您选择了"+usercount+"个人参与会议，需要付费：￥"+(parseFloat(usercount)*USER_PUB_PAY)+"，确定要付费吗？")){
			return false;
		}else{
			return true;
		}
	}
	return true;
}

//预约会议界面，版定用户选择
jQuery(function(){
	if($('#start_time').length >0){
		$('#start_time').datepicker({minDate:new Date()});
	}
	$('#left_user_list :checkbox').live('click',function(evt){
		if($('#right_user_list :checkbox').length >=200){
			alert("最多选择200个参会用户");
			if(evt.stopPropagation){
				evt.stopPropagation();
			}else{
				window.event.cancelBubble = true; 
			}
			return false;
		}
    	$('#right_user_list').append($(this).attr('checked',true).parent().remove());
    	caclSelectedUser($('#right_user_list :checkbox').length);
    });

	$('#right_user_list :checkbox').live('click',function(){
    	$('#left_user_list').append($(this).attr('checked',false).parent().remove());
    	caclSelectedUser($('#right_user_list :checkbox').length);
    });
});

function selectAll(){
	$('#left_user_list :checkbox').each(function(){
		if($('#right_user_list :checkbox').length <200){
			$('#right_user_list').append($(this).attr('checked',true).parent().remove());
		}
    });
	caclSelectedUser($('#right_user_list :checkbox').length);
}

function unSelectAll(){
	$('#right_user_list :checkbox').each(function(){
		$('#left_user_list').append($(this).attr('checked',false).parent().remove());
    });
	caclSelectedUser($('#right_user_list :checkbox').length);
}

function caclSelectedUser(num){
	$('#selectedUser').html("("+num+"位)");
}