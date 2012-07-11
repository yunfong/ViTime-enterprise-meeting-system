<?php
require_once SERVICE_DIR.'admin/AdminManage.php';
/**
 * 系统管理
 *  
 * @author gray.liu
 * @email gaoomei@gmail.com
 * @date 2012-4-21
 */
class Admin extends CU_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->_needValidLogin(true);
	}
	
	/**
	 * 首页 - 企业用户管理
	 */
	public function index(){
		$this->_remap('listcompany');
	}
	
	/**
	 * 列出企业用户
	 */
	public function listcompany(){
		$page = $this->input->get('page',TRUE);
		$service = AdminManage::getInstance();
		$companylist = $service->listCmpAdmin($page);
		$this->displayHtml($companylist);
	}
	
	public function update_company($cmp_admin_id = null,$cmp_id = null){
		if(!$this->input->is_post()){
			if(empty($cmp_admin_id)){
				$this->_redirect('listcompany');
			}else{
				$company_admin = AdminManage::getInstance()->getCompany($cmp_admin_id,$cmp_id);
				return $this->displayHtml($company_admin);
			}
			return;
		}
		
		if(empty($_POST)){
			redirect('/'.$this->_controller.'/listcompany','location');
		}
		
		$postData = $this->input->post(NULL,TRUE);
		$errMsg = '';
		
		//去掉html标签
		foreach($postData as $k=>&$v){
			$v = trim(strip_tags($v));
		}
		
		$errMsg = '';
		if(empty($postData['username'])){
			$errMsg .= $this->wrapErrorMsg("用户名必须填写");
		}
		
		if(empty($postData['mobile'])){
			$errMsg .= $this->wrapErrorMsg("手机号码必须填写");
		}
		
		if(empty($postData['email'])){
			$errMsg .= $this->wrapErrorMsg("邮箱必须填写");
		}
		
		if(empty($postData['company_name'])){
			$errMsg .= $this->wrapErrorMsg("公司名称必须填写");
		}
		
		if(empty($postData['company_mark'])){
			$errMsg .= $this->wrapErrorMsg("企业标识必须填写");
		}
		
		if(empty($postData['tryDate'])){
			unset($postData['tryDate']);
			unset($postData['tryDate']);
		}else{
			if(!preg_match("/\d{4}-\d{2}-\d{2}/", $postData['tryDate'])){
				$errMsg .= $this->wrapErrorMsg("试用收回日期格式不正确，正确格式：2012-01-01");
			}
		}
		
		if(!empty($errMsg)){
			$errMsg = "填写不完整：<br />{$errMsg}";
			return $this->displayHtml(array('errMsg'=>$errMsg));
		}
		
		$loginMsg = AdminManage::getInstance()->updateCmpAdmin($postData);
		if($loginMsg === TRUE){
			redirect("/{$this->_controller}/update_company_success/", 'location');
		}else{
			$company_admin = AdminManage::getInstance()->getCompany($postData['user_id'],$postData['company_id']);
			return $this->displayHtml(array_merge($company_admin,array('errMsg'=>"修改失败，$loginMsg")));
		}
		
	}
	
	/**
	 * 修改成功
	 */
	public function update_company_success(){
		$this->displayHtml();
	}
	
	/**
	 * 删除企业
	 */
	public function delete_company(){
		if(!$this->input->is_post()){
			$this->_redirect('listcompany');
		}
		$user_id = $this->input->post('user_id',true);
		$cmp_id = $this->input->post('cmp_id',true);
		if(empty($user_id) || empty($cmp_id)){
			$this->_redirect('listcompany');
		}
		
		if(empty($user_id) || empty($cmp_id)){
			$this->_redirect('listcompany');
		}
		
		$delRs = AdminManage::getInstance()->deleteCmpAdmin($user_id,$cmp_id);
		if($delRs === TRUE){
			$_SESSION['delete_company_success'] = $user_id.'-'.$cmp_id;
			$this->_redirect('delete_company_result');
		}else{
			$_SESSION['delete_company_failure'] = '删除失败，'.$delRs;
			$this->_redirect('delete_company_result');
		}
	}
	
	/**
	 * 显示删除结果
	 */
	public function delete_company_result(){
		//删除成功
		if(!empty($_SESSION['delete_company_success'])){
			$id = $_SESSION['delete_company_success'];
			$ids = explode('-', $id);
			unset($_SESSION['delete_company_success']);
			$company = AdminManage::getInstance()->getCompany($ids[0], $ids[1]);
			$this->displayHtml($company,'delete_company_success');
		}else {
			//删除失败
			if(empty($_SESSION['delete_company_failure'])){
				$this->_redirect('listcompany');
			}else{
				$failureMsg = $_SESSION['delete_company_failure'];
				unset($_SESSION['delete_company_failure']);
				$this->displayHtml(array('errMsg'=>$failureMsg),'delete_company_failure');
			}
		}
	}
	

	public function change_password(){
		$this->displayHtml();
	}
	
	public function do_change_password(){
		if(!$this->input->is_post() || empty($_POST)){
			$this->_redirect('change_password');
		}
		$postData = $this->input->post(NULL,TRUE);
		if(empty($postData['password']) || empty($postData['newpassword'])){
			$postData['errMsg'] = "密码不能为空，必须填写";
			$this->displayHtml($postData,'change_password');
		}
		$rs = AdminManage::getInstance()->changePassword($postData['password'], $postData['newpassword']);
		if($rs === true){
			$_SESSION['change_password_success'] = $rs;
			$this->_redirect('change_password_success');
		}else{
			$postData['errMsg'] = $rs;
			$this->displayHtml($postData,'change_password');
		}
	}
	
	public function change_password_success(){
		if(empty($_SESSION['change_password_success'])){
			$this->_redirect('change_password');
		}else{
			unset($_SESSION['change_password_success']);
			$this->displayHtml();
		}
	}
	
	/**
	 * 用户资料
	 */
	public function profile(){
		if(!$this->input->is_post() || empty($_POST)){
			return $this->displayHtml($this->_user->toArray());
		}
		
		$postData = $this->input->post(NULL,TRUE);
		$errMsg = '';
		
		//去掉html标签
		foreach($postData as $k=>&$v){
			$v = trim(strip_tags($v));
		}
		
		$errMsg = '';
		if(empty($postData['mobile'])){
			$errMsg .= $this->wrapErrorMsg("手机号码必须填写");
		}
		
		if(empty($postData['email'])){
			$errMsg .= $this->wrapErrorMsg("邮箱必须填写");
		}
		
		if(!empty($errMsg)){
			$companyUser = $this->_user->toArray();
			$errMsg = "填写不完整：<br />{$errMsg}";
			return $this->displayHtml(array_merge($companyUser,array('errMsg'=>$errMsg)));
		}
		
		$loginMsg = AdminManage::getInstance()->updateSelfInfo($postData);
		if($loginMsg === TRUE){
			$this->_redirect('profile_update_success');
		}else{
			return $this->displayHtml(array_merge($this->_user->toArray(),array('errMsg'=>"修改失败，$loginMsg")));
		}
	}
	
	public function profile_update_success(){
		$this->displayHtml();
	}
	
	/** 
	 * 是否有权限
	 */
	protected function _has_permissions_do() {
		return !empty($this->_user) && $this->_user->isSysAdmin();
	}
	
	
}