<?php
require_once SERVICE_DIR.'company/CmpUserManage.php';
require_once SERVICE_DIR.'company/CmpAdminManage.php';
require_once SERVICE_DIR.'meeting/MeetingManage.php';

class Tourist extends CU_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->_needValidLogin(false);
	}
	
	public function index(){
		$this->_redirect('public_meeting');
	}
	

	
	
	/**
	 * 公共会议列表
	 */
	public function public_meeting(){
		$page = $this->input->get('page',true);
		
		$meetingList = MeetingManage::getInstance()->listPubMeeting($page);
		$this->displayHtml($meetingList);
	}
	
	/**
	 * 进入会议
	 * @param unknown_type $meet_id
	 */
	public function enter_meeting($meet_id){
		if(empty($meet_id)){
			$this->back();
		}
		$meeting = MeetingManage::getInstance()->getMeetingInfo($meet_id);
		if(empty($meeting['password'])){
			$this->_redirect('meeting');
		}
		$meeting['_action'] = 'public_meeting';
		$this->displayHtml($meeting);
	}
	
	public function do_enter_meeting(){
		if(empty($_POST)){
			$this->back();
		}
		$meeting_id = $this->input->post('meet_id',true);
		$password = $this->input->post('password',TRUE);
		$meeting = MeetingManage::getInstance()->getMeetingInfo($meeting_id);
		if(empty($password)){
			$meeting['errMsg'] = '密码不能为空';
			$this->displayHtml($meeting,'enter_meeting');
		}else{
			if($password !=  $meeting['password']){
				$meeting['errMsg'] = '密码错误，请重新输入';
				$this->displayHtml($meeting,'enter_meeting');
			}else{
				$this->_redirect('meeting');
			}
		}
		
	}
	
	public function meeting(){
		exit('meeting');
	}
	
	private function back(){
		exit('<script>window.history.back()</script>');
	}
	
	protected function _has_permissions_do() {
		return true;
	}
	
	

}

?>