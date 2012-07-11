/**
 * 
 */

function validUpdateCmpForm(form){}

function toUpdateCmp(cmp_admin_id,cmp_id){
	window.location.href = '/admin/update_company/'+cmp_admin_id+'/'+cmp_id;
}

function delete_company(cmp_admin_id,cmp_id){
	if(!confirm("您确定要删除该企业用户？")){
		return false;
	}
	
	var form = document.forms['deletecompanyForm'];
	form.user_id.value = cmp_admin_id;
	form.cmp_id.value = cmp_id;
	form.submit();
	return false;
}

$(function(){
	var timeInput = $('.time');
	if(timeInput.length > 0){
		timeInput.datepicker({minDate:new Date()});
	}
});