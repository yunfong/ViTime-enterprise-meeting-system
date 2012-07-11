<?php
require_once SERVICE_DIR.'meeting/MeetingManage.php';

/**
 * 会议相关控制器
 */

class Meeting extends CU_Controller{
	
	/**
	 * 进入会议
	 * @param int $meet_id
	 */
	public function index($meet_id = null){
		
		if($this->input->is_post()){
			return $this->do_enter_meeting();
		}
		
		
		if(empty($meet_id)){
			show_404();
		}
		
		$meeting = MeetingManage::getInstance()->getMeetingInfo($meet_id);
		if(empty($meeting)){
			show_404();
		}
		
		if(strtotime($meeting['end_time']) < time()){
			$this->_showError('该会议已经过期',array('url'=>'javascript:window.history.back();','label'=>'返回'));
		}
		
		if($_SESSION['view-meeting-'.$meet_id] == $meet_id){
			return $this->view($meeting);
		}
		
		if($meeting['type'] == 1){
			if(!UserSession::isLogin()){
				$meeting['to_url'] = SROOT.'meeting/index/'.$meet_id;
				return $this->displayHtml($meeting,'login');
			}
			if($meeting['user_id'] != $this->_user->id && !MeetingManage::getInstance()->havePureToViewMeeting($meet_id)){
				$to_url = array('url'=>'javascript:window.history.back()','label'=>'返回');
				show_error(array('您没有权限参与该会议','to_url'=>$to_url),200,'权限错误');
			}
			$_SESSION['view-meeting-'.$meet_id] = $meet_id;
			$this->view($meeting);
		}else{
			if(empty($meeting['password'])){
				$this->view($meeting);
			}else{
				$this->displayHtml($meeting,'enter_meeting');
			}
		}
				
	}
	
	/**
	 * 刷出会议界面
	 * @param int $meet_id
	 */
	private function view($meeting){
		
		$user = $this->_user;
		
		$userName= $user->username; 
		$mediaServer="m.cecall.cc";
		$role = (UserSession::isLogin() && $meeting['user_id'] == $this->_user->id)?2:4;  //4:普通用户 2:管理员
		$password=md5("123456");
		$roomID=$meeting['id'];
		$scriptType="php";
		$realName=$user->name?"{$user->name}":"{$user->username}";
		$connStr="userName={$userName}&realName={$realName}&&password={$password}&mediaServer={$mediaServer}&role={$role}&roomID={$roomID}&scriptType={$scriptType}";
		$_SESSION['view-meeting-role-'.$roomID] = $role;
		$this->displayHtml(array('connStr'=>$connStr),$script='',$template_dir = '',$renderHF = false);
	}
	
	/**
	 * 进入会议需要密码
	 */
	private function do_enter_meeting(){
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
				$_SESSION['view-meeting-'.$meeting_id] = $meeting_id;
				redirect('/meeting/index/'.$meeting_id);
			}
		}
	}
	
	/**
	 * 验证用户是否合法
	 */
	public function checkuser(){
		$meet_id =  $this->input->get('roomID');
		$role=$this->input->get('role');
		if($role != 2 && $role != 4){
			echo "<Result isUser='false'>","</Result>";
			exit();
		}
		
		$good_role = $_SESSION['view-meeting-role-'.$meet_id] ;
		if($role != $good_role){
			echo "<Result isUser='false'>","</Result>";
			exit();
		}
		
		if(empty($meet_id) || $_SESSION['view-meeting-'.$meet_id] != $meet_id){
			echo "<Result isUser='false'>","</Result>";
			exit();
		}
		
		if($_SESSION['view-meeting-'.$meet_id] == $meet_id){
			echo "<Result isUser='true'>","</Result>";
			exit();
		}
		
		$meeting = MeetingManage::getInstance()->getMeetingInfo($meet_id);
		
		if($meeting['type'] == 1){
			if(!UserSession::isLogin()){
				echo "<Result isUser='false'>","</Result>";
				exit();
			}
			if($meeting['user_id'] != $this->_user->id && !MeetingManage::getInstance()->havePureToViewMeeting($meet_id)){
				echo "<Result isUser='false'>","</Result>";
				exit();
			}
			echo "<Result isUser='true'>","</Result>";
			exit();
		}else{
			if(empty($meeting['password'])){
				echo "<Result isUser='true'>","</Result>";
				exit();
			}else{
				echo "<Result isUser='false'>","</Result>";
				exit();
			}
		}
		
	}
	
	//跳转到正确的swf文件
	public function cecallmeetswf(){
		redirect('/CeCallMeet.swf');
	}
	
	/* *
	 *
	 * @see CU_Controller::_has_permissions_do()
	 */
	protected function _has_permissions_do() {
		return true;
	}

}