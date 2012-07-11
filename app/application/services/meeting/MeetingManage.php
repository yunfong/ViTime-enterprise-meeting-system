<?php
require_once SERVICE_DIR.'meeting/IMeetingManage.php';
require_once SERVICE_DIR.'UserSession.php';
/**
 * 会议相关类
 *  
 * @author gray.liu
 * @email gaoomei@gmail.com
 * @date 2012-4-22
 */
class MeetingManage implements IMeetingManage{

	/**
	 * @var CI_Controller
	 */
	public $CI;
	
	/**
	 * 实例
	 * @var MeetingManage
	 */
	private static $_instance;
	
	/**
	 * @var IUser
	 */
	private $_user;
	
	public function __construct(){
		$this->CI = &get_instance();
	}
	
	/**
	 * @return MeetingManage
	 */
	public static function getInstance(){
		if(self::$_instance instanceof MeetingManage){
			return self::$_instance;
		}else{
			return self::$_instance = new MeetingManage();
		}
	}
	
	public function getUser(){
		if(empty($this->_user)){
			return $this->_user = UserSession::getUser();
		}else{
			return $this->_user;
		}
	}
	
	/**
	 * 读取企业会议列表*
	 *
	 * @param int $page
	 * @param int $limit 
	 * @see ICompanyManage::listCmpMeeting()
	 */
	public function listCmpMeeting($page = 1,$limit = 10) {
		$company_id = $this->getUser()->company_id;
		$this->CI->load->model('meeting/Meeting_model','MeetingModel');
		return $this->CI->MeetingModel->getCompanyMeetingList($this->getUser()->id,$company_id,$page,$limit);
	}
	
	/**
	 * 读取会议参与人员
	 * @param unknown_type $meeting_id
	 */
	public function listCmpMeetingUser($meeting_id){
		$this->CI->load->model('meeting/Meetinguserlog_model','MeetingUserModel');
		return $this->CI->MeetingUserModel->getMeetingUserList($meeting_id);
		
	}

	/**
	 * 读取公共会议列表*
	 *
	 * @param int $page
	 * @param int $limit 
	 * @see ICompanyManage::listCmpMeeting()
	 */
	public function listPubMeeting($page = 1,$limit = 10) {
		$this->CI->load->model('meeting/Meeting_model','MeetingModel');
		return $this->CI->MeetingModel->getPublicMeetingList($this->getUser()->id,$page,$limit);
	}

	/** 
	 * 发布企业会议
	 * @param array $postData
	 * @return 
	 */
	public function bookMeeting($postData) {
		$user_list = explode(',', $postData['user_list']);
		$user_list = array_unique($user_list);
		$user_list = array_filter($user_list); 
		$meeting = new Meet();	
		$meeting->title = $postData['title'];
		$meeting->user_id = $this->getUser()->id;
		$meeting->company_id = $this->getUser()->company_id;
		$start_time = strtotime($postData['start_time'].' '.$postData['hour'].':'.$postData['minutes'].':00');
		
		$meeting->start_time = date('Y-m-d H:i:s',$start_time);
		$meeting->end_time = date('Y-m-d H:i:s',$start_time + (intval($postData['time_length'])*60 ));
		$meeting->type = 1;
		$meeting->state = 1;
		$meeting->time_length = intval($postData['time_length']);
		
		$meeting->usercount = count($user_list);
		
		$this->CI->load->model('meeting/Meeting_model','MeetingModel');
		//开启事务
		$this->CI->db->trans_begin();
		$meet_id = $this->CI->MeetingModel->newMeeting($meeting->toArray());
		if(empty($meet_id) || !is_numeric($meet_id)){
			$this->CI->db->trans_rollback();
			return "预约会议失败";
		}
		$this->CI->load->model('meeting/Meetinguserlog_model','MeetingUserModel');
		foreach($user_list as $userid){
			$data = array('meet_id'=>$meet_id,'user_id'=>$userid);
			$rs = $this->CI->MeetingUserModel->insert($data);
			if(empty($rs) || !is_numeric($rs)){
				$this->CI->db->trans_rollback();
				return "预约会议失败";
			}
		}
		$this->CI->db->trans_commit();
		return $meet_id;
	}

	/** 
	 * 发布公共会议
	 * @param array $postData
	 * @return 
	 */
	public function bookPublicMeeting($postData) {
		$meeting = new Meet();	
		$meeting->title = strip_tags($postData['title']);
		$meeting->user_id = $this->getUser()->id;
		$meeting->company_id = $this->getUser()->company_id;
		$start_time = strtotime($postData['start_time'].' '.$postData['hour'].':'.$postData['minutes'].':00');
		
		$meeting->start_time = date('Y-m-d H:i:s',$start_time);
		$meeting->end_time = date('Y-m-d H:i:s',$start_time + (intval($postData['time_length'])*60 ));
		$meeting->type = 0;
		$meeting->state = 1;
		$meeting->password = strip_tags($postData['password']);
		$meeting->usercount = intval($postData['usercount']);
		$meeting->time_length = intval($postData['time_length']);
		$this->CI->db->trans_begin();
		$this->CI->load->model('meeting/Meeting_model','MeetingModel');
		$meet_id = $this->CI->MeetingModel->newMeeting($meeting->toArray());
		if(empty($meet_id) || !is_numeric($meet_id)){
			$this->CI->db->trans_rollback();
			return "预约会议失败";
		}
		$chargeMonth = (intval($postData['usercount']) * floatval(USER_PUB_PAY));
		$rs = CmpAdminManage::getInstance()->chargeback($chargeMonth);
		if(!$rs){
			$this->CI->db->trans_rollback();
			return "扣取费用失败";
		}
		$record = array(
				'member_id'=>$this->getUser()->id,
				'company_id'=>$this->getUser()->company_id,
				'r_money'=>$chargeMonth,
				'pay_type'=>1,
				'note'=>'公共会议支付费用'
			);
		$this->CI->load->model('user/Pay_records_model','PayRecord');
		$this->CI->PayRecord->newRecord($record);
		$this->CI->db->trans_commit();
		return $meet_id;
	}
	
	/**
	 * 读取会议信息
	 * @param unknown_type $meeting_id
	 */
	public function getMeetingInfo($meeting_id){
		$this->CI->load->model('meeting/Meeting_model','MeetingModel');
		$meeting = $this->CI->MeetingModel->get($meeting_id);
		if(!empty($meeting)){
			$meeting['user_list'] = $this->listCmpMeetingUser($meeting_id);
		}
		return $meeting;
	}
	/* *
	 * 取消会议
	 * @see IMeetingManage::cancelMeeting()
	 */
	public function cancelMeeting($meeting_id) {
		$this->CI->load->model('meeting/Meeting_model','MeetingModel');
		if(empty($meeting_id)){
			return "参数错误";
		}
		$meeting = $this->CI->MeetingModel->get($meeting_id);
		if($meeting['company_id']!=0 && $this->getUser()->company_id != $meeting['company_id']){
			return '您没有权限修改该会议';
		}
		
		if($this->getUser()->id != $meeting['user_id'] && !$this->getUser()->isCmpAdmin()){
			return '您没有权限修改该会议';
		}
		
		
		if(!empty($meeting_id)){
			$rs = $this->CI->MeetingModel->update(array('state'=>0),array('id'=>$meeting_id));
			if($rs == 1){
				$this->CI->load->model('meeting/Meetinguserlog_model','MeetingUserModel');
				$this->CI->MeetingUserModel->delete(array('meet_id'=>$meeting_id));
				return true;
			}
		}		
		return false;
	}

	/* *
	 * 修改企业会议
	 * @see IMeetingManage::changeMeeting()
	 */
	public function changeMeeting($postData) {
		$this->CI->load->model('meeting/Meeting_model','MeetingModel');
		$meet_id = $postData['meet_id'];
		if(empty($meet_id)){
			return "参数错误";
		}
		$meeting = $this->CI->MeetingModel->get($meet_id);
		if(empty($meeting)){
			return "该会议不存在";
		}
		
		if($meeting['company_id']!=0 && $this->getUser()->company_id != $meeting['company_id']){
			return '您没有权限修改该会议';
		}
		
		if($this->getUser()->id != $meeting['user_id'] && !$this->getUser()->isCmpAdmin()){
			return '您没有权限修改该会议';
		}
		
		$user_list = explode(',', $postData['user_list']);
		$user_list = array_unique($user_list);
		$user_list = array_filter($user_list); 
		$meeting = new Meet();	
		$meeting->title = $postData['title'];
		$start_time = strtotime($postData['start_time'].' '.$postData['hour'].':'.$postData['minutes'].':00');
		
		$meeting->start_time = date('Y-m-d H:i:s',$start_time);
		$meeting->end_time = date('Y-m-d H:i:s',$start_time + (intval($postData['time_length'])*60 ));
		$meeting->time_length = intval($postData['time_length']);
		
		$meeting->usercount = count($user_list);
		
		
		//开启事务
		$this->CI->db->trans_begin();
		$rs = $this->CI->MeetingModel->update($meeting->toArray(),array('id'=>$meet_id));
		if(is_null($rs) || !is_numeric($rs)){
			$this->CI->db->trans_rollback();
			return "编辑会议失败";
		}
		$this->CI->load->model('meeting/Meetinguserlog_model','MeetingUserModel');
		$this->CI->MeetingUserModel->delete(array('meet_id'=>$meet_id));
		foreach($user_list as $userid){
			$data = array('meet_id'=>$meet_id,'user_id'=>$userid);
			$rs = $this->CI->MeetingUserModel->insert($data);
			if(empty($rs) || !is_numeric($rs)){
				$this->CI->db->trans_rollback();
				return "修改会议失败";
			}
		}
		$this->CI->db->trans_commit();
		return $meet_id;
	}

	/**
	 * 修改公共会议
	 * @param unknown_type $postData
	 */
	public function changePublicMeeting($postData,$chargeMoney){
		$meet_id = $postData['meet_id'];
		
		$oldMeeting = $this->getMeetingInfo($meet_id);
		$meeting = new Meet();	
		$meeting->title = strip_tags($postData['title']);
		$start_time = strtotime($postData['start_time'].' '.$postData['hour'].':'.$postData['minutes'].':00');
		$meeting->start_time = date('Y-m-d H:i:s',$start_time);
		$meeting->end_time = date('Y-m-d H:i:s',$start_time + (intval($postData['time_length'])*60 ));
		$meeting->password = strip_tags($postData['password']);
		$meeting->usercount = intval($postData['usercount']);
		$meeting->time_length = intval($postData['time_length']);
		
		$this->CI->load->model('user/Pay_records_model','PayRecord');
		$this->CI->db->trans_begin();
		$this->CI->load->model('meeting/Meeting_model','MeetingModel');
		$rs = $this->CI->MeetingModel->update($meeting->toArray(),array('id'=>$meet_id));
		if(empty($rs) || !is_numeric($rs)){
			$this->CI->db->trans_rollback();
			return "编辑会议失败";
		}
//		$chargeMoney = (intval($postData['usercount']) * floatval(USER_PUB_PAY));
		if($chargeMoney > 0){
			$rs = CmpAdminManage::getInstance()->chargeback($chargeMoney);
			if(!$rs){
				$this->CI->db->trans_rollback();
				return "扣取费用失败";
			}
			$record = array(
					'member_id'=>$this->getUser()->id,
					'company_id'=>$this->getUser()->company_id,
					'r_money'=>$chargeMoney,
					'pay_type'=>1,
					'note'=>'编辑公共会议支付费用'
				);
			$this->CI->PayRecord->newRecord($record);
		}
		$this->CI->db->trans_commit();
		
		return $meet_id;
	}
/* *
	 *
	 * @see IMeetingManage::viewMeeting()
	 */
	public function viewMeeting($meeting_id) {
		
		
	}

	public function havePureToViewMeeting($meeting_id){
		$userid = $this->getUser()->id;
		$this->CI->load->model('meeting/Meetinguserlog_model','MeetingUserModel');
		$meetingLog = $this->CI->MeetingUserModel->getMeetByMUid($meeting_id,$userid);
		return !empty($meetingLog);
	}
	
	/**
	 * 获取会议记录
	 * @param int $page
	 * @param int $limit
	 */
	public function getMeetingRecord($page = 1,$limit = 10){
		$userid = $this->getUser()->id;
		$this->CI->load->model('meeting/Meeting_model','MeetingModel');
		return $this->CI->MeetingModel->getMeetingRecord($userid,$page,$limit);
	}
	
}

?>